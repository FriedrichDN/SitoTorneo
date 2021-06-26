<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["userID"])){
  header("location: /privato/index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include_once "meta.php" ?>
  <link rel="stylesheet" href="css/login.css?ts=<?=time()?>&quot">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
  <header>
    <div>
      <h3 class="float-md-start mb-0">Accedi</h3>
      <?php include_once "navbar.php";?>
    </div>
  </header>
  <main class="form-signin">
    <form method="post" action="script/login-script.php">
      <h1 class="h3 mb-3 fw-normal">Esegui il log in</h1>
      <div class="form-floating">
      <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Inserisci lo username">
      <label class= "input" for="floatingInput">Username</label>
      </div>
      <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Inserisci la Password">
      <label class= "input" for="floatingPassword">Password</label>
      </div>

      <div class="checkbox mb-3">
        <label>
            <p>Non hai un account? <a href="register.php">Registrati</a>.</p>
        </label>
      </div>
            <input class="w-100 btn btn-lg btn-primary" type="submit" name="submit" value="Invia">
    </form>
  </main>
    <?php
    if (isset($_GET["error"])){
      switch ($_GET["error"]) {
        case 'emptyinput':
        echo "<p>Il form non é completo</p>";
        break;
        case 'wrongcredentials':
        echo "<p>Username e/o Password sbagliate</p>";
        break;
        case 'statementfailed01':
        echo "<p>Contatta l'amministratore del sito, Errore 01</p>";
        break;
        default:
        break;
      }
    }
    ?>
    <?php include_once "footer.php";?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
