<?php
  session_start();

  if(isset($_GET['dir'])){
    $dir = '.'.$_GET['dir'];

    if(is_dir($dir)){
      if($dh = opendir($dir)){
        while(($file = readdir($dh)) && $file !== '.' && $file !== '..'){
          $files - array();
          array_push($files, $file);
        }
        $_SESSION['folder'] = $files;
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
  }
  else{
    $_SESSION['upmsg'] = "Błąd serwera";
    header('Location: ../app.php');
  }
?>
