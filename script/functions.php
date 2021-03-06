<?php
function emptyInputRegister($username,$password,$confirm_password) {
  $result;
  if (empty($username) || empty($password) || empty($confirm_password) ){
    $result=true;
  }
  else {
    $result=false;
  }
  return $result;
}
function invalidUsername($username) {
  $result;
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)){
    $result=true;
  }
  else {
    $result=false;
  }
  return $result;
}
/*function invalidPassword($password) {
$result;
if (!) ){
$result=true;
}
else {
$result=false;
}
return $result;
}
*/
function notEqualPassword($password,$confirm_password) {
  $result;
  if ($password !== $confirm_password){
    $result=true;
  }
  else {
    $result=false;
  }
  return $result;
}
function usernameExist($connection,$username) {
  $sql = "SELECT * FROM users WHERE username = ?;";
  $statement = mysqli_stmt_init($connection);
  if (!mysqli_stmt_prepare($statement,$sql)){
    header ("location: ../register.php?error=statementfailed01");
    exit();

  }
  mysqli_stmt_bind_param($statement, "s", $username);
  mysqli_stmt_execute($statement);
  $resultData = mysqli_stmt_get_result($statement);

  if ($row = mysqli_fetch_assoc($resultData)){
    return $row;
  }
  else {
    $result= false;
    return $result;
  }
  mysqli_stmt_close($statement);
}
function createUser($connection,$username,$password) {
  $sql = "INSERT INTO users (username,password) VALUES (?,?);";
  $statement = mysqli_stmt_init($connection);
  if (!mysqli_stmt_prepare($statement,$sql)){
    header ("location: ../register.php?error=statementfailed02");
    exit();

  }
  $hashpassword = password_hash($password, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($statement, "ss", $username,$hashpassword);
  mysqli_stmt_execute($statement);
  mysqli_stmt_close($statement);
  header ("location: ../register.php?error=none");
  exit();
}

function emptyInputLogin($username,$password) {
  $result;
  if (empty($username) || empty($password)){
    $result=true;
  }
  else {
    $result=false;
  }
  return $result;
}

function loginUser($connection,$username,$password){
  $usernameExist = usernameExist($connection,$username);
  if ($usernameExist == false){
    header("location: ../login.php?error=wrongcredentials");
    exit();
  }

  $passwordHashed = $usernameExist["password"];
  $checkPassword = password_verify($password,$passwordHashed);

  if ($checkPassword === false) {
    header ("location: ../login.php?error=wrongcredentials");
    exit();
  }
  else if($checkPassword === true) {
    session_start();
    $_SESSION["userID"] = $usernameExist["userID"];
    $_SESSION["username"] = $usernameExist["username"];
    header ("location: ../privato/index.php");
    exit();
  }
}
function sqlquery($sql){
  include $_SERVER['DOCUMENT_ROOT']."/script/config.php";
  $result= $connection->query($sql);
  $connection -> close();
  return $result;
}
function matchfinder($matchtype) {
  if ($matchtype<=6 && $matchtype>=1){
    $uri = 'http://api.football-data.org/v2/competitions/CL/matches?stage=GROUP_STAGE&&matchday='.$matchtype;
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response);
  }
  else {
    $uri = 'http://api.football-data.org/v2/competitions/CL/matches?stage='.$matchtype;
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response);
  }
  return $matches;
}
function punteggio (){
  $cont=0;
  $WINNER="DEFAULT";
  $status=1;
  $sql = "SELECT fase FROM torneo";
  $result= sqlquery($sql);
  $ris = $result->fetch_assoc();
  $matchtype = $ris["fase"];
  $matches = matchfinder($matchtype);
  if (!is_null($matches)){
    foreach($matches->matches as $match){
      $cont++;
      if ($match->status!="FINISHED"){
        $status=0;
      }
    }
    if ($status==1){
      $it = new FilesystemIterator('../privato/risultati/');
      $filename = array();
      $files = array();
      foreach ($it as $fileinfo) {
        $file = $fileinfo->getFilename();
        array_push ($files,$file);
        array_push ($filename,basename($file,".json"));
      }
      $risesatto = array_fill(0, $cont, 0);
      $matchesatto = array_fill(0, $cont, 0);
      foreach($files as $filesname) {
        $i=0;
        $name = basename($filesname,".json");
        $sql = "SELECT PartiteIndovinate FROM users WHERE username= '$name'";
        $result = sqlquery($sql);
        $row = $result->fetch_assoc();
        $PartiteIndovinate = $row["PartiteIndovinate"];
        $sql = "SELECT RisultatiEsatti FROM users WHERE username= '$name'";
        $result = sqlquery($sql);
        $row = $result->fetch_assoc();
        $RisultatiEsatti = $row["RisultatiEsatti"];
        foreach($matches->matches as $match){
          $risultato = $match->score->fullTime->homeTeam ."-".$match->score->fullTime->awayTeam;
          $json= file_get_contents("../privato/risultati/".$filesname);
          $decode = json_decode($json,true);
          $risinserito = $decode[$i];
          if ($risinserito[0]!= "?"){
            if ((int)$risinserito[0] > (int)$risinserito[2]){
              $WINNER="HOME_TEAM";
            }
            elseif ((int)$risinserito[0] < (int)$risinserito[2]){
              $WINNER="AWAY_TEAM";
            }
            elseif ((int)$risinserito[0] == (int)$risinserito[2]){
              $WINNER="DRAW";
            }
            if ($WINNER== $match->score->winner){
              $matchesatto[$i]++;
              $PartiteIndovinate++;
            }
            if ($risultato == $risinserito ){
              $risesatto[$i]++;
              $RisultatiEsatti++;
            }
          }
          $i++;
        }

        $sql = "UPDATE users SET PartiteIndovinate = '$PartiteIndovinate' WHERE username= '$name'";
        $result = sqlquery($sql);
        $sql = "UPDATE users SET RisultatiEsatti = '$RisultatiEsatti' WHERE username= '$name'";
        $result = sqlquery($sql);


      }
      if ($matchtype >= 1 && $matchtype < 6){
        $matchtype++;
        $matchtype = "$matchtype";
        $punteggio1 = 20;
        $punteggio2 = 20;
      }
      elseif ($matchtype===6) {
        $matchtype="LAST_16";
        $punteggio1 = 30;
        $punteggio2 = 30;
      }
      elseif ($matchtype==="LAST_16") {
        $matchtype="QUARTER_FINAL";
        $punteggio1 = 45;
        $punteggio2 = 45;
      }
      elseif ($matchtype==="QUARTER_FINAL") {
        $matchtype="SEMI_FINAL";
        $punteggio1 = 70;
        $punteggio2 = 70;
      }
      elseif ($matchtype==="SEMI_FINAL") {
        $matchtype="FINAL";
        $punteggio1 = 100;
        $punteggio2 = 200;
      }
      $sql = "UPDATE torneo SET fase='$matchtype'";
      $result = sqlquery($sql);
      $y=0;
      foreach($files as $filesname) {
        $i=0;
        $file= $filename[$y];
        $sql = "SELECT punti FROM users WHERE username= '$file'";
        $result = sqlquery($sql);
        while($row = $result->fetch_assoc()) {
          $punti=$row["punti"];
        }
        foreach($matches->matches as $match){
          $risultato = $match->score->fullTime->homeTeam ."-".$match->score->fullTime->awayTeam;
          $json= file_get_contents("../privato/risultati/".$filesname);
          $decode = json_decode($json,true);
          $risinserito = $decode[$i];
          if ($risinserito[0]!= "?"){
            if ((int)$risinserito[0] > (int)$risinserito[2]){
              $WINNER="HOME_TEAM";
            }
            elseif ((int)$risinserito[0] < (int)$risinserito[2]){
              $WINNER="AWAY_TEAM";

            }
            elseif ((int)$risinserito[0] == (int)$risinserito[2]){
              $WINNER="DRAW";
            }
            if ($WINNER== $match->score->winner){
              if ($matchesatto[$i]!=0){
                $punti= $punti + ($punteggio1/$matchesatto[$i]);
              }
              else {
                $punti= $punti + $punteggio1;
              }
            }
            if ($risultato == $risinserito){
              if ($risesatto[$i]!=0){
                $punti= $punti + ($punteggio2/$risesatto[$i]);
              }
              else {
                $punti= $punti + $punteggio2;
              }
            }
          }
          $i++;

        }
        $sql = "UPDATE users SET punti='$punti' WHERE username='$file'";
        $result = sqlquery($sql);
        $y++;
      }
      $files = glob('../privato/risultati/*');
      foreach($files as $file){
        if(is_file($file)){
          unlink($file);
        }
      }
    }
  }
  $cont=0;
  $i=0;
  $uri = 'http://api.football-data.org/v2/competitions/CL/matches?stage=GROUP_STAGE&&matchday='.$matchtype;
  $reqPrefs['http']['method'] = 'GET';
  $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
  $stream_context = stream_context_create($reqPrefs);
  $response = file_get_contents($uri, false, $stream_context);
  $matches = json_decode($response);
  foreach($matches->matches as $match){
    $cont++;
  }
    foreach($matches->matches as $match){
      $i++;
      if ($i==$cont){
        $matchdate=$match->utcDate;
      }
    }
    if ($matchdate[5]==0){

      $lastmonth=$matchdate[6];

    }
    else{
      $lastmonth=$matchdate[5].$matchdate[6];
    }
    if ($matchdate[8]==0){
      $lastday=$matchdate[9];

    }
    else{
      $lastday=$matchdate[8].(string)($matchdate[9]+1);
    }


  $crontab = "0 0 $lastday $lastmonth * /usr/bin/php /var/www/SitoTorneo/script/punteggio.php \n";
  file_put_contents("/var/spool/cron/crontabs/root", $crontab);
}
function matchtype(){
  $sql = "SELECT fase FROM torneo";
  $result= sqlquery($sql);
  $ris = $result->fetch_assoc();
  $matchtype = $ris["fase"];
  $status=1;
  while ($status != 0) {
    if ($matchtype<=6 && $matchtype>=1){
      $uri = 'http://api.football-data.org/v2/competitions/CL/matches?stage=GROUP_STAGE&&matchday='.$matchtype;
      $reqPrefs['http']['method'] = 'GET';
      $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
      $stream_context = stream_context_create($reqPrefs);
      $response = file_get_contents($uri, false, $stream_context);
      $matches = json_decode($response);
    }
    else {
      $uri = 'http://api.football-data.org/v2/competitions/CL/matches?stage='.$matchtype;
      $reqPrefs['http']['method'] = 'GET';
      $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
      $stream_context = stream_context_create($reqPrefs);
      $response = file_get_contents($uri, false, $stream_context);
      $matches = json_decode($response);
    }
    foreach($matches->matches as $match){
      if ($match->status!="FINISHED"){
        $status=0;
      }
    }
    if ($status==1) {
      if ($matchtype >= 1 && $matchtype < 6){
        $matchtype++;
      }
      elseif ($matchtype===6) {
        $matchtype="LAST_16";
      }
      elseif ($matchtype==="LAST_16") {
        $matchtype="QUARTER_FINAL";
      }
      elseif ($matchtype==="QUARTER_FINAL") {
        $matchtype="SEMI_FINAL";
      }
      elseif ($matchtype==="SEMI_FINAL") {
        $matchtype="FINAL";
      }
    }
  }
  return $matchtype;
}
function checkdates(){
  $matchtype= matchtype();
  $cont=0;
  $i=0;
  $status=1;
  $uri = 'http://api.football-data.org/v2/competitions/CL/matches?stage=GROUP_STAGE&&matchday='.$matchtype;
  $reqPrefs['http']['method'] = 'GET';
  $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
  $stream_context = stream_context_create($reqPrefs);
  $response = file_get_contents($uri, false, $stream_context);
  $matches = json_decode($response);
  foreach($matches->matches as $match){
    $cont++;
  }
    foreach($matches->matches as $match){
      $i++;
      if ($i==$cont){
        $matchdate=$match->utcDate;
      }
    }
    if ($matchdate[5]==0){

      $lastmonth=$matchdate[6];

    }
    else{
      $lastmonth=$matchdate[5].$matchdate[6];
    }
    if ($matchdate[8]==0){
      $lastday=$matchdate[9];

    }
    else{
      $lastday=$matchdate[8].(string)($matchdate[9]+1);
    }


  $crontab = "0 0 $lastday $lastmonth * /usr/bin/php /var/www/SitoTorneo/script/punteggio.php \n";
  file_put_contents("/var/spool/cron/crontabs/root", $crontab);
  $sql = "UPDATE checkdate SET stato=1";
  $result = sqlquery($sql);
  $sql = "UPDATE torneo SET fase=$matchtype";
  $result = sqlquery($sql);
}
