<?php
  session_start();

  // if login and password are not set -> get back to login page and exit script
  if(!isset($_POST['login']) || !isset($_POST['password'])){
    header('Location: ../index.php');
    exit();
  }
  else{

    // convert connection errors into exceptions
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    // error handling with try-catch
    try{
      // new connection obj, throw exception if connection fails
      $connection = new mysqli($host, $db_user, $db_pass, $db_name);
      if($connection->connect_errno!=0){
        throw new Exception(mysqli_connect_errno());
      }
      else{
        // save data from form into variables
        $login = $_POST["login"];
        $password = $_POST["password"];

        // prevent from sql injection (add HTML entities)
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");

        // Remember inputs values
        $_SESSION['input_login'] = $login;
        $_SESSION['input_password'] = $password;

        // convert to variables with entities to string
        // does login exist?
        if($result = $connection->query(
        sprintf("SELECT * FROM users_test WHERE user = '%s'",
        mysqli_real_escape_string($connection, $login)))){

          $login_amount = $result->num_rows;

          // check if such login exists
          if($login_amount>0){
            // fetch mysql table
            $row = $result->fetch_assoc();

            $_SESSION['user'] = $row['user'];

            if($logs = $connection->query("SELECT * FROM logs WHERE user = '".$_SESSION["user"]."' AND type = '0'")){
              $bad_logs = $logs->num_rows;
              if($bad_logs >2){
                $_SESSION['e_loginerr'] = 'Zbyt duża liczba błędnych logowań';
                header('Location: ../index.php');
              }
              else{
                $bad_log_table = $connection->query("SELECT * FROM logs WHERE date IN (SELECT MAX(date) FROM logs WHERE user = '".$_SESSION["user"]."' AND type = '0')");
                $bad_log_row = $bad_log_table->fetch_assoc();
                $_SESSION['bad_log_time'] = $bad_log_row['date'];

                if($password == $row['pass']){
                  if($connection->query("DELETE FROM logs WHERE user = '".$_SESSION["user"]."'")){
                    if($connection->query("INSERT INTO logs VALUES (NULL, '".$_SESSION["user"]."', '1', now())")){
                      $_SESSION['logged'] = true;
                      // set cookie for logining page
                      setcookie("login", $login, time() + (86400 * 30), "/");

                      // delete session vars
                      unset($_SESSION['error']);
                      unset($_SESSION['input_login']);
                      unset($_SESSION['input_password']);

                      // free memmory
                      $result->close();
                      // render app page
                      header('Location: ../app.php');
                    }
                    else{
                      $_SESSION['e_loginerr'] = 'Błąd odczytu z bazy';
                      header('Location: ../index.php');
                    }
                  }
                  else{
                    $_SESSION['e_loginerr'] = 'Błąd odczytu z bazy';
                    header('Location: ../index.php');
                  }
                }
                else{
                  if($connection->query("INSERT INTO logs VALUES (NULL, '".$_SESSION["user"]."', 0, now())")){
                    // if password doesnt match
                    $_SESSION['e_loginerr'] = 'Nieprawidłowy login lub hasło';
                    header('Location: ../index.php');
                  }
                  else{
                    $_SESSION['e_loginerr'] = 'Błąd odczytu z bazy';
                    header('Location: ../index.php');
                  }
                }
              }
            }
          }
          else{
            // if login doesnt exist
            $_SESSION['e_loginerr'] = 'Nieprawidłowy login lub hasło';
            header('Location: ../index.php');
          }
          // close reports stream and connection
          mysqli_report(MYSQLI_REPORT_OFF);
          $connection->close();
        }
        // if connection fails throw exception
        else{
          throw new Exception($connection->error);
        }
      }
    }
    // display exceptions
    catch(Exception $e){
      echo 'Błąd serwera.';
      echo '<br />info dev '.$e;
    }
  }

?>
