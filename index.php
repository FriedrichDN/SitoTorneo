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
<html lang="it" class="h-100">
<head>
  <?php include "meta.php"; ?>
</head>
<body class="d-flex h-100 text-center text-white bg-dark">

  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
      <div>
        <h3 class="float-md-start mb-0">Home Page</h3>
        <?php include_once "navbar.php";?>
      </div>
    </header>

    <main class="px-3">
      <h1>Torneo Pescara Cinema e Cazzi Vari</h1>
      <p class="lead">Sei pronto ad un duello all'ultimo sangue a colpi di pronostici ?! Allora esegui il log in e prova a vincere una cena gratis</p>
    </main>
    <?php include_once "footer.php";?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
