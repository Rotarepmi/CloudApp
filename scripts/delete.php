<?php
  session_start();

  // retrieve GET request
  if(isset($_GET['dir'])){
    // add dot - direcotory is absolute - we want to go out of the catalogue
    $dir = '.'.$_GET['dir'];

    // get only filename from the path link
    $exploded = explode("/", $dir);
    $file = end($exploded);

    // tell wheteher the dir is a directory
    if(is_dir($dir)){
      // if true - remove direcotory
      // if statement for errors handling
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
      // if false - delete file
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
