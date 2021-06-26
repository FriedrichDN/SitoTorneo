<nav class="nav nav-masthead justify-content-center float-md-end">

    <?php
    if (!isset($_SESSION["userID"])){

      echo "<a class=\"nav-link\" href=\"classifica.php\">Classifica</a>";

      echo "<a class=\"nav-link\" href=\"partite.php\">Partite</a>";

      echo "<a class=\"nav-link\" href=\"login.php\">Login</a>";

      echo "<a class=\"nav-link\" href=\"register.php\">Registrati</a>";


    }
    else {

      echo "<a class=\"nav-link\" href=\"classifica.php\">Classifica</a>";

      echo "<a class=\"nav-link\" href=\"partite.php\">Partite</a>";

      echo "<a class=\"nav-link\" href=\"../script/logout-script.php\">Esci</a>";

    }
    ?>
  </nav>
