<?php
session_start();

if(!isset($_SESSION["userID"])){
  header("location: ../login.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <title>Torneo Pescara Cinema e Cazzi Vari</title>
  <meta name="author"  content="Federico De Nuccio">
  <meta name="description"  content="sito torneo del gruppo Pescara cinema e cazzi vari">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- collegamento ai css -->
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/mobile.css">
  <link rel="stylesheet" href="../css/style.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="../css/mobile.css?ts=<?=time()?>&quot">
</head>
<body>
  <header>
    <div>
      <h1 id="Titolo">Torneo Pescara Cinema e Cazzi Vari</h1>
    </div>
    <div id= "Logo">
      <a href="#"><img src="../immagini/img.png" width="100" height="97" alt="Logo"></a>
    </div>
  </header>
  <?php include_once "../navbar.php";?>
  <?php include_once "../footer.php";?>
