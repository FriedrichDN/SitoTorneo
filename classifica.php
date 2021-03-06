<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["userID"])){
  header("location: /privato/index.php");
  exit;
}
include $_SERVER['DOCUMENT_ROOT']."/script/functions.php";
?>
<!DOCTYPE html>
<html lang="it" class="h-100">
<head>
  <?php include_once $_SERVER['DOCUMENT_ROOT']."/meta.php" ?>
  <link rel="stylesheet" type="text/css" href="css/classifica.css?<?php echo time(); ?>" />
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
      <div>
        <h3 class="float-md-start mb-0">Classifica</h3>
        <?php include_once $_SERVER['DOCUMENT_ROOT']."/navbar.php";?>
      </div>
    </header>
    <main class="px-3">
      <div class="row justify-content-center">
        <?php
        $sql = "SELECT username,punti,RisultatiEsatti,PartiteIndovinate FROM users ORDER BY punti DESC, PartiteIndovinate DESC";
        $result = sqlquery($sql);
        if ($result->num_rows > 0) {
          echo "<table class=\"table table-responsive\"><tr><th scope=\"col\" >Giocatori</th><th scope=\"col\">Punti</th><th scope=\"col\">Risultati Esatti</th> <th scope=\"col\">Partite Indovinate</th></tr>";
          // output data of each row
          while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["username"]. "</td><td>" . $row["punti"]. "</td><td>" . $row["RisultatiEsatti"]. "</td><td>" . $row["PartiteIndovinate"]. "</td></tr>";
          }
          echo "</table>";
        }
        ?>
      </div>
    </main>
    <?php include_once $_SERVER['DOCUMENT_ROOT']."/footer.php";?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
