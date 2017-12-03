<?php
  session_start();
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
          <h5 class="modal-title">Zaloguj się</h5>
        </div>
        <div class="modal-body">
          <?php
            if(isset($_SESSION['success_msg'])){
              echo '<div class="alert alert-success mt-2">'.$_SESSION['success_msg'].'</div>';
              unset($_SESSION['success_msg']);
            }
          ?>
          <form class="text-center" action="scripts/login.php" method="post">
            <input class="form-control" type="text" name="login" required placeholder="Login"
            value="<?php
              // if already registered - input_login is handled into Session
              // inputs are handled into Session (they dont disappear after refresh)
              if(isset($_SESSION['input_login'])){
                echo $_SESSION['input_login'];
                unset($_SESSION['input_login']);
              }
              // use cookie value
              else if(isset($_COOKIE['login'])){
                echo $_COOKIE['login'];
              }
            ?>">

            <input class="form-control mt-3" type="password" name="password" required placeholder="Hasło"
            value="<?php
              if(isset($_SESSION['input_password'])){
                echo $_SESSION['input_password'];
                unset($_SESSION['input_password']);
              }
            ?>">

            <?php
              if(isset($_SESSION['e_loginerr'])){
                echo '<div class="alert alert-danger mt-2">'.$_SESSION['e_loginerr'].'</div>';
                unset($_SESSION['e_loginerr']);
              }
            ?>

            <button type="submit" class="btn btn-outline-success mt-3">Zaloguj</button>
          </form>
        </div>
        <div class="modal-footer">
          <a href="register.php">Nie posiadasz konta? - Zarejestruj się</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Include javasript plugins -->
  <?php require_once "../modules/plugins.php"; ?>

</body>
</html>
