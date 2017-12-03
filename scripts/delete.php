<?php
  session_start();

  if(isset($_GET['dir'])){
    $dir = '.'.$_GET['dir'];
    $exploded = explode("/", $dir);
    $file = end($exploded);

    if(is_dir($dir)){
      if(rmdir($dir)){
        $_SESSION['upmsg'] = "Pomyślnie usunięto folder $file";
        header('Location: ../app.php');
      }
      else{
        $_SESSION['upmsg'] = "Błąd serwera";
        header('Location: ../app.php');
      }
    }
    else{
      if(unlink($dir)){
        $_SESSION['upmsg'] = "Pomyślnie usunięto plik $file";
        header('Location: ../app.php');
      }
      else{
        $_SESSION['upmsg'] = "Błąd serwera";
        header('Location: ../app.php');
      }
    }
  }
  else{
    $_SESSION['upmsg'] = "Błąd serwera";
    header('Location: ../app.php');
  }
?>
