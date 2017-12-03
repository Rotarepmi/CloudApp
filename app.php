<?php
  session_start();

  // iff user isnt logged in - render login page and exit this script
  if(!isset($_SESSION['logged'])){
    header('Location: index.php');
    exit();
  }
?>
<!DOCTYPE html>
<html lang="pl">
<head>

<!-- Include metatags -->
<?php require_once "../modules/meta.php"; ?>

<link rel="stylesheet" href="css/style.min.css">
<link rel="stylesheet" href="css/fonts/font-awesome/css/font-awesome.min.css">
</head>
<body>

  <!-- Navgation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/">Jakub Mandra</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/">Home</a>
          </li>
          <!-- Show username stored in cookie -->
          <li class="navbar-text">
            - Zalogowano jako <b><?php echo $_COOKIE['login'] ?></b> -
          </li>
          <li class="nav-item">
            <a class="nav-link" href="scripts/logout.php">Wyloguj</a>
          </li>
        </ul>

      </div>
    </div>
  </nav> <!-- End of navigation elements -->

  <main class="container-fluid">

    <div class="row">
      <div class="col menu">
        <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#sendFileModal'>Wyślij plik</button>
        <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#newFolderModal'>Nowy folder</button>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <?php
        // messages from the server - stored in session variables
          if(isset($_SESSION['bad_log_time'])){
            echo '<div class="alert alert-danger mt-2 mx-auto">'.$_SESSION['bad_log_time'].' zanotowano błędne logowanie!</div>';
            unset($_SESSION['bad_log_time']);
          }
        ?>
        <?php
          if(isset($_SESSION['upmsg'])){
            echo '<div class="alert alert-primary mt-2 mx-auto">'.$_SESSION['upmsg'].'</div>';
            unset($_SESSION['upmsg']);
          }
        ?>

        <div id="alert"></div>
      </div>
    </div>

    <div class="row">
      <div class="col">
        <div class="files-wrapp">
          <?php
          // build breadcrumbs - if dir isnt direction - rewrite dir with variable from GET request
          // (GET sent on folder icon click)
            if(!isset($_GET['dir'])){
              $_SESSION['dir'] = './storage/'.$_SESSION['user'];

              echo '<div class="breadcrumbs">
                <a href="app.php">'.$_SESSION["user"].'/</a>
              </div>';
            }
            else{
              $_SESSION['dir'] = './storage/'.$_SESSION['user'].'/'.$_GET['dir'];

              echo '<div class="breadcrumbs">
                <a href="app.php">'.$_SESSION["user"].'/</a>
                <a href="?dir='.$_GET[dir].'">'.$_GET['dir'].'/</a>
              </div>';
            }

            $dir = $_SESSION['dir'];


            // Open a known directory, and proceed to read its contents
            if(is_dir($dir)){
              if($dh = opendir($dir)){
                while(($file = readdir($dh)) && $file !== '.' && $file !== '..'){

                  // test if dir is file or folder
                  if(is_file($dir.'/'.$file)){
                    echo "
                    <div class='file'>
                      <a href='$dir/$file' class='file-link'>
                        <i class='fa fa-file-text-o' aria-hidden='true'></i>
                        <p>
                          $file
                        </p>
                      </a>
                      <div class='file-buttons'>
                      
                        <button type='button' class='file-btn download-btn' data-file='$dir/$file' data-toggle='modal' data-target='#downloadModal'>
                          <i class='fa fa-download' aria-hidden='true'></i>
                        </a>

                        <button type='button' class='file-btn delete-btn' data-file='$dir/$file' data-toggle='modal' data-target='#deleteModal'>
                          <i class='fa fa-trash' aria-hidden='true'></i>
                        </button>

                      </div>
                    </div>";
                  }
                  else{
                    echo "
                    <div class='file'>
                      <a href='?dir=$file' class='file-link'>
                        <i class='fa fa-folder-open-o' aria-hidden='true'></i>
                        <p>
                          $file
                        </p>
                      </a>
                      <div class='file-buttons'>
                        <button type='button' class='file-btn delete-btn' data-file='$dir/$file' data-toggle='modal' data-target='#deleteModal'>
                          <i class='fa fa-trash' aria-hidden='true'></i>
                        </button>
                      </div>
                    </div>";
                  }
                }
                closedir($dh);
              }
            }
          ?>
        </div>
      </div>
    </div>

  </main>

  <!-- sendFile Modal -->
  <div class="modal fade" id="sendFileModal" tabindex="-1" role="dialog" aria-labelledby="sendFileModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Wyślij plik (max 3MB)</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="file-form mx-auto" action="./scripts/store.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
              <input class="form-control-file" type="file" name="file" accept=".jpg, .jpeg, .png, .gif, .pdf, .txt"/>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="submitFileForm"><i class="fa fa-circle-o-notch fa-spin"></i> Wyślij</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Powrót</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- newFolder Modal -->
  <div class="modal fade" id="newFolderModal" tabindex="-1" role="dialog" aria-labelledby="newFolderModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Dodaj nowy folder</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="./scripts/new_folder.php" method="POST">
            <div class="form-group">
              <label for="foldername">Nazwa folderu</label>
              <input type="text" class="form-control" name="foldername" />
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Dodaj nowy folder</a>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Powrót</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  <!-- download Modal -->
  <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Pobieranie pliku</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="downloadTitle"></p>
        </div>
        <div class="modal-footer">
          <a href='#' class="btn btn-primary" id="download" download>Pobierz</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Powrót</button>
        </div>
      </div>
    </div>
  </div>

  <!-- delete Modal -->
  <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Usuwanie pliku</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p id="deleteTitle"></p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" id="delete">Usuń</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Powrót</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Include javasript plugins -->
  <?php require_once "../modules/plugins.php"; ?>

  <script src="./js/scripts.js"></script>
</body>
</html>
