<?php
session_start();

if(!isset($_SESSION["userID"])){
  header("location: ../login.php");
  exit;
}
include '../script/functions.php';
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <?php include_once "../meta.php" ?>
  <link rel="stylesheet" type="text/css" href="../css/partite.css?<?php echo time(); ?>" />
  <link rel="stylesheet" type="text/css" href="../css/style.css?<?php echo time(); ?>" />
</head>
<body class="d-flex h-100 text-center text-white bg-dark">
  <div class="cover-container d-flex w-100 h-100 p-3 mx-auto flex-column">
    <header class="mb-auto">
      <div>
        <h3 class="float-md-start mb-0">Lista Partite</h3>
        <?php include_once "../navbar.php";?>
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
        $num=0; //numero match
        ?>
        <table class="table table-responsive">
          <form method="post" action="../script/json.php">
            <?php
            echo "<tr><th scope=\"col\" >Casa</th><th scope=\"col\">Ospiti</th><th scope=\"col\">Risultato</th></tr>";
            $filename= "risultati/".$_SESSION["username"]. ".json";
            if (!file_exists($filename)){
              foreach ($matches->matches as $match) {
                $status=1;
                if($match->status!="FINISHED"){
                  $status=0;
                }
                $num++;
                ?>
                <tr>
                  <td><?php echo $match->homeTeam->name; ?></td>
                  <td><?php echo $match->awayTeam->name; ?></td>
                  <?php
                  $fullTime=$match->score->fullTime;
                  switch ($match->status) {
                    case 'FINISHED':
                    echo "<td>$fullTime->homeTeam - $fullTime->awayTeam</td>";
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
                  ?>
                  <?php
                  if($match->status=="SCHEDULED"){
                    echo "<td><input type=\"text\" Placeholder=\"Risultato match\" name=\"match_$num\" required></td>";
                  }
                  ?>
                </tr>
              <?php } ?>
              <?php
              if ($status==0){
                echo "<input class=\"w-100 btn btn-lg btn-primary\" type=\"submit\" name=\"submit\" value=\"Invia\">";
              }
              ?>
            <?php }
            else{
              foreach ($matches->matches as $match) {?>
                <tr>
                  <td><?php echo $match->homeTeam->name; ?></td>
                  <td><?php echo $match->awayTeam->name; ?></td>
                  <?php
                  $fullTime=$match->score->fullTime;
                  switch ($match->status) {
                    case 'FINISHED':
                    echo "<td>$fullTime->homeTeam - $fullTime->awayTeam</td>";
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
                  ?>
                <?php }} ?>
              </form>
            </table>
          </div>
        </main>
        <?php include_once "../footer.php";?>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </body>
    </html>
