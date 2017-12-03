<?php
  // close session, delete session data and go to login page
  session_start();

  session_unset();

  header('Location: ../index.php');
?>
