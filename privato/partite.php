<?php
session_start();

if(!isset($_SESSION["userID"])){
    header("location: /../login.php");
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
	<link rel="stylesheet" type="text/css" href="../css/partite_privato.css">
  <link rel="stylesheet" type="text/css" href="../css/partite_mobile.css">
  <link rel="stylesheet" type="text/css" href="../css/style.css">
  <link rel="stylesheet" type="text/css" href="../css/mobile.css">
  <link rel="stylesheet" href="../css/partite_privato.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="../css/partite_mobile.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="../css/style.css?ts=<?=time()?>&quot">
  <link rel="stylesheet" href="../css/mobile.css?ts=<?=time()?>&quot">
</head>
<body>
  		<header>
				<div id="Titolo">
					<h1>Torneo Pescara Cinema e Cazzi Vari</h1>
          <h2 id="Partite">Lista Partite</h2>
    			</div>
    			<div id= "Logo">
    				<a href="index.php"> <img src="../immagini/img.png" width="100" height="97" alt="Logo"> </a>
    			</div>
			</header>
      <?php include_once "../navbar.php";?>
      <div id="content">
<?php
    include '../script/functions.php';
    punteggio();
    $sql = "SELECT fase FROM torneo";
    $result= sqlquery($sql);
    $ris = $result->fetch_assoc();
    $matchtype = $ris["fase"];
    $matches= matchfinder($matchtype);
    $num=0; //numero match
    ?>
    <table>
        <form method="post" action="../script/json.php">
            <?php
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
					   <td>vs</td>
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
                  echo "<input type=\"submit\" name=\"submit\">";
                  }
                  ?>
				<?php }
        else{
				  foreach ($matches->matches as $match) {?>
                    <tr>
                        <td><?php echo $match->homeTeam->name; ?></td>
					   <td>vs</td>
                        <td><?php echo $match->awayTeam->name; ?></td>
                        <td><?php echo $match->score->fullTime->homeTeam; ?></td>
                        <td>-</td>
                        <td><?php echo $match->score->fullTime->awayTeam; ?></td>
				   </tr>

				 <?php }} ?>

        </form>
    </table>
      </div>
			<?php include_once "../footer.php";?>
</body>
</html>
