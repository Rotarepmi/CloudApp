<?php
  session_start();

  if(isset($_POST['login'])){
    // Validation success (when val. fails - set to false)
    $success = true;

    // read vars
    $login = $_POST['login'];
    $pass1 = $_POST['pass'];
    $pass2 = $_POST['pass2'];

    // Check if passwords match
    if($pass1 != $pass2){
      $success = false;
      $_SESSION['e_pass2'] = "Niepoprawne hasło.";
      header ('Location: ../register.php');
    }

    // Validate Captcha
    $secret_key = "6LdzyzMUAAAAAMMpytpuTCU6cuzh0sySsMYWxzbd";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
    $response = json_decode($check);

    if(!$response->success){
      $success = false;
      $_SESSION['e_captcha'] = "Potwierdź, że jesteś człowiekiem.";
      header ('Location: ../register.php');
    }

    // Remember inputs values
    $_SESSION['input_login'] = $login;
    $_SESSION['input_pass'] = $pass1;
    $_SESSION['input_pass2'] = $pass2;

    // include db data
    require_once "connect.php";
    // convert connection errors into exceptions
    mysqli_report(MYSQLI_REPORT_STRICT);

    // error handling with try-catch
    try{
      // new connection obj, throw exception if connection fails
      $connection = new mysqli($host, $db_user, $db_pass, $db_name);
      if($connection->connect_errno!=0){
        throw new Exception(mysqli_connect_errno());
      }
      else{
        // does login exist?
        $result = $connection->query("SELECT id FROM users_test WHERE user='$login'");

        // throw exception when connection fails
        if(!$result){
          throw new Exception($connection->error);
        }

        // check if such login already exists
        $login_amount = $result->num_rows;

        if($login_amount>0){
          $success = false;
          $_SESSION['e_login'] = "Konto o podanym loginie już istnieje.";
          header ('Location: ../register.php');
        }

        if($success){
          // Add user to db
          if($connection->query("INSERT INTO users_test VALUES (NULL, '$login', '$pass1')")){
            mkdir('../storage/'.$login, 0777, true);
            $_SESSION['register_success'] = true;
            $_SESSION['success_msg'] = "Rejestracja udana - możesz się zalogować";

            // Delete inputs values
            if(isset($_SESSION['input_pass'])) unset($_SESSION['input_pass']);
            if(isset($_SESSION['input_pass2'])) unset($_SESSION['input_pass2']);

            // Delete registration errors
            if(isset($_SESSION['e_pass'])) unset($_SESSION['e_pass']);
            if(isset($_SESSION['e_captcha'])) unset($_SESSION['e_captcha']);

            header('Location: ../index.php');
          }
          // throw exception when writing into db fails
          else{
            throw new Exception($connection->error);
          }
        }

        // close reports stream and connection
        mysqli_report(MYSQLI_REPORT_OFF);
        $connection->close();
      }
    }
    // display exceptions
    catch(Exception $e){
      echo 'Błąd serwera.';
      echo '<br />info dev '.$e;
    }
  }
?>
