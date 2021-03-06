<?php
  session_start();

  // directory is set into app.php
  $uploads_dir = '.'.$_SESSION['dir'].'/';
  // max size of file - 3MB
  $max_size = 3000000;

  // file is sent by POST form method
  if(is_uploaded_file($_FILES['file']['tmp_name'])){
    // try whether file size is lower than 3MB
    if($_FILES['file']['size'] > $max_size){
      $_SESSION['upmsg'] = "Zbyt duży rozmiar pliku";
      header('Location: ../app.php');
    }
    else{
      // save uploaded file into directory
      if(move_uploaded_file($_FILES['file']['tmp_name'], $uploads_dir.$_FILES['file']['name'])){

        $_SESSION['upmsg'] = "Pomyślnie zapisano plik ".$_FILES['file']['name'];
        header('Location: ../app.php');
      }
      else{
        $_SESSION['upmsg'] = "Błąd przesyłu danych";
        header('Location: ../app.php');
      }
    }
  }
  else{
    $_SESSION['upmsg'] = "Wybierz plik do przesłania";
    header('Location: ../app.php');
  }

?>
