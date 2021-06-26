<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["userID"])){
    header("location: /privato/index.php");
    exit;
}
include 'script/functions.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include_once "meta.php" ?>
  <link rel="stylesheet" type="text/css" href="css/partite.css?<?php echo time(); ?>" />
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
      <header class="mb-auto">
        <div>
          <h3 class="float-md-start mb-0">Lista Partite</h3>
          <?php include_once "navbar.php";?>
        </div>
      </header>
      <main class="px-3">
        <div class="row justify-content-center">
        <?php
        punteggio();
        $sql = "SELECT fase FROM torneo";
        $result= sqlquery($sql);
        $ris = $result->fetch_assoc();
        $matchtype = $ris["fase"];
        $matches= matchfinder($matchtype);
        ?>
        <table class="table table-responsive">
                <?php
                echo "<tr><th scope=\"col\" >Casa</th><th scope=\"col\">Ospiti</th><th scope=\"col\">Risultato</th></tr>";
                foreach ($matches->matches as $match) {
                ?>
                        <tr>
                            <td><?php echo $match->homeTeam->name; ?></td>

                            <td><?php echo $match->awayTeam->name; ?></td>
                            <?php
                            $fullTime=$match->score->fullTime;
                            switch ($match->status) {
                              case 'FINISHED':
                              echo "<td>$fullTime->homeTeam</td>";
					         echo "<td>-</td>";
                              echo "<td>$fullTime->awayTeam</td>";
                                break;
                              case 'CANCELED':
                              echo "<td>Partita Cancellata</td>";
                              break;
                              case 'POSTPONED':
                              echo "<td>Partita Rimandata</td>";
                              break;
                              case 'SUSPENDED':
                              echo "<td>Partita Sospesa</td>";
                              break;
                              case 'IN_PLAY':
                              echo "<td>Partita in corso</td>";
                              break;
                              case 'PAUSED':
                              echo "<td>Partita in corso - INTERVALLO</td>";
                              break;
                              default:
                                break;
                            }
                          }
                            ?>
                            </table>
                          </div>
                        </main>
                              <?php include_once "footer.php";?>
                              </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
