<?php
  session_start();

  $uploads_dir = '.'.$_SESSION['dir'].'/';

  if(isset($_POST['foldername'])){
    $foldername = $_POST['foldername'];
    $structure = $uploads_dir.$foldername;

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
