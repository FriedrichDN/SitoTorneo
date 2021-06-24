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
	<title>Torneo Pescara Cinema e Cazzi Vari</title>
	<meta name="author"  content="Federico De Nuccio">
	<meta name="description"  content="sito torneo del gruppo Pescara cinema e cazzi vari">
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- collegamento ai css -->
	<link rel="stylesheet" type="text/css" href="css/login.css">
  <link rel="stylesheet" type="text/css" href="css/login_mobile.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/mobile.css">
  <link rel="stylesheet" href="css/login.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="css/login_mobile.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="css/style.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="css/mobile.css?ts=<?=time()?>&quot">

</head>
<body>
  		<header>
				<div id="Titolo">
					<h1>Torneo Pescara Cinema e Cazzi Vari</h1>
    		</div>
    		<div id= "Logo">
    			<a href="index.php"><img src="immagini/img.png" width="100" height="97" alt="Logo"></a>
    		</div>
  		</header>
    	<div id="content">
         <form method="post" action="script/login-script.php">
             <input type="text" name="username" placeholder="Inserisci lo username">
             <input type="password" name="password" placeholder="Inserisci la Password">
             <input type="submit" name="submit" value="Invia">
             <p>Non hai un account? <a href="register.php">Registrati</a>.</p>
         </form>
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
      </div>
      <?php include_once "footer.php";?>
</body>
</html>
