<?php
  session_start();

  $uploads_dir = '.'.$_SESSION['dir'].'/';
  $max_size = 3000000;

  if(is_uploaded_file($_FILES['file']['tmp_name'])){
    if($_FILES['file']['size'] > $max_size){
      $_SESSION['upmsg'] = "Zbyt duży rozmiar pliku";
      header('Location: ../app.php');
    }
    else{
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
