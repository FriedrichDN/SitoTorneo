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
  <?php include_once $_SERVER['DOCUMENT_ROOT']."/meta.php" ?>
  <link rel="stylesheet" href="css/register.css?ts=<?=time()?>&quot">
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header>
      <h3 class="float-md-start mb-0">Registrati</h3>
      <?php include_once $_SERVER['DOCUMENT_ROOT']."/navbar.php";?>
    </header>
    <main class="form-signin">
      <form method="post" action="script/register-script.php">
        <h1 class="h3 mb-3 fw-normal">Esegui la registrazione</h1>
        <div class="form-floating">
          <input type="text" class="form-control" id="floatingInput" name="username" placeholder="Inserisci lo username">
          <label class= "input" for="floatingInput">Username</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control secondo" id="floatingInput" name="password" placeholder="Inserisci la Password">
          <label class= "input" for="floatingInput">Password</label>
        </div>
        <div class="form-floating">
          <input type="password" class="form-control" id="floatingInput" name="confirm_password" placeholder="Ripeti la Password">
          <label class= "input" for="floatingInput">Ripeti la Password</label>
        </div>
        <div class="checkbox mb-3">
          <p>Possiedi giá un account? <a href="login.php">Esegui il login</a>.</p>
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
        case 'invalidusername':
        echo "<p>Lo username non é valido, inserisci solo lettere e numeri (senza spazi),non inserire caratteri speciali come : ! ” # $ % & ’ ( ) * + , - . / : ; < = > ? @ [ \ ] ^ _ ` { | } ~ </p>";
        break;
        case 'usernametaken':
        echo "<p>Lo username inserito é giá stato scelto</p>";
        break;
        case 'statementfailed01':
        echo "<p>Contatta l'amministratore del sito, Errore 01</p>";
        break;
        case 'statementfailed02':
        echo "<p>Contatta l'amministratore del sito, Errore 02</p>";
        break;
        case 'none':
        echo "<p>Registrazione avvenuta con successo!</p>";
        break;
        default:
        break;
      }
    }
    ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT']."/footer.php"; ?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
