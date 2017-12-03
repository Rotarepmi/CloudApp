<?php
  session_start();

  // if user is logged in - render app page and exit this script
  if(isset($_SESSION['logged'])){
    header('Location: app.php');
    exit();
  }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <!-- Include metatags -->
  <?php require_once "../modules/meta.php"; ?>

  <link rel="stylesheet" href="css/style.min.css">
</head>

<body>

  <?php include_once "../modules/nav.php"; ?>

  <div class="row">
    <div class="col-sm-8 col-md-6 col-lg-5 mx-auto">
      <div class="user-modal mt-2">
        <div class="modal-header">
          <h5 class="modal-title">Rejestracja użytkownika</h5>
        </div>
        <div class="modal-body">

          <form class="text-center" action="scripts/register_script.php" method="post">
            <div class="form-group">
              <input class="form-control" type="text" name="login" placeholder="Login"
              value="<?php
                // inputs are handled into Session (they dont disappear after refresh)
                if(isset($_SESSION['input_login'])){
                  echo $_SESSION['input_login'];
                  unset($_SESSION['input_login']);
                }
              ?>">

              <?php
                // displays exception errors
                if(isset($_SESSION['e_login'])){
                  echo '<div class="alert alert-danger mt-2">'.$_SESSION['e_login'].'</div>';
                  unset($_SESSION['e_login']);
                }
              ?>
            </div>

            <div class="form-group">
              <input class="form-control" type="password" name="pass" placeholder="Hasło"
              value="<?php
                if(isset($_SESSION['input_pass'])){
                  echo $_SESSION['input_pass'];
                  unset($_SESSION['input_pass']);
                }
              ?>">

              <?php
                if(isset($_SESSION['e_pass'])){
                  echo '<div class="alert alert-danger mt-2">'.$_SESSION['e_pass'].'</div>';
                  unset($_SESSION['e_pass']);
                }
              ?>
            </div>

            <div class="form-group">
              <input class="form-control" type="password" name="pass2" placeholder="Powtórz hasło"
              value="<?php
                if(isset($_SESSION['input_pass2'])){
                  echo $_SESSION['input_pass2'];
                  unset($_SESSION['input_pass2']);
                }
              ?>">

              <?php
                if(isset($_SESSION['e_pass2'])){
                  echo '<div class="alert alert-danger mt-2">'.$_SESSION['e_pass2'].'</div>';
                  unset($_SESSION['e_pass2']);
                }
              ?>
            </div>

            <div class="g-recaptcha" data-sitekey="6LdzyzMUAAAAAFmADdJgUbpZq1eoV0AIFSrlH4Qk"></div>
            <?php
              if(isset($_SESSION['e_captcha'])){
                echo '<div class="alert alert-danger mt-2">'.$_SESSION['e_captcha'].'</div>';
                unset($_SESSION['e_captcha']);
              }
            ?>

            <button type="submit" class="btn btn-outline-success mt-3">Zarejestruj się</button>
          </form>
        </div>
        <div class="modal-footer">
          <a href="index.php">Masz już konto? - Zaloguj się</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Include javasript plugins -->
  <?php require_once "../modules/plugins.php"; ?>

  <script src='https://www.google.com/recaptcha/api.js'></script>
  <script type-"text/javascript">
  // bootstraps modal display function
  $(document).ready(function() {
    $('#registerModal').modal({
      show: true,
      backdrop: 'static',
      keyboard: false
    });
  });
  </script>
</body>
</html>
