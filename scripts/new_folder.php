<?php
  session_start();

  // dir set by app.php
  $uploads_dir = '.'.$_SESSION['dir'].'/';

  // POST form method
  if(isset($_POST['foldername'])){
    $foldername = $_POST['foldername'];
    // extend direction
    $structure = $uploads_dir.$foldername;

    // make new direction with 0777 - read/write
    if(mkdir($structure, 0777, true)){
      $_SESSION['upmsg'] = "Pomyślnie dodano folder $foldername";
      header('Location: ../app.php');
    }
    else{
      $_SESSION['upmsg'] = "Błąd serwera";
      header('Location: ../app.php');
    }
  }
  else{
    $_SESSION['upmsg'] = "Błąd serwera";
    header('Location: ../app.php');
  }

?>
