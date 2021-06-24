<nav>

  <ul id="menu">
<?php
if (!isset($_SESSION["userID"])){

echo "<li><a href=\"classifica.php\">Classifica</a></li>";

echo "<li><a href=\"partite.php\">Partite</a></li>";

echo "<li><a href=\"login.php\">Login</a></li>";

echo "<li><a href=\"register.php\">Registrati</a></li>";

echo "</ul>";

}
  else {

echo "<li><a href=\"classifica.php\">Classifica</a></li>";

echo "<li><a href=\"partite.php\">Partite</a></li>";

echo "<li><a href=\"../script/logout-script.php\">Esci</a></li>";

echo "</ul>";
  }
?>
</nav>
