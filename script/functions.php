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
  include "config.php";
  $result= $connection->query($sql);
  $connection -> close();
  return $result;
}
function matchfinder($matchtype) {
  if (!is_string($matchtype)){
  $uri = 'http://api.football-data.org/v2/competitions/EC/matches/?matchday='.$matchtype;
  $reqPrefs['http']['method'] = 'GET';
  $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
  $stream_context = stream_context_create($reqPrefs);
  $response = file_get_contents($uri, false, $stream_context);
  $matches = json_decode($response);
  }
  else {
  $uri = 'http://api.football-data.org/v2/competitions/EC/matches/?stage='.$matchtype;
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
          }
      if ($risultato == $risinserito ){
          $risesatto[$i]++;
        }
      }
     $i++;

      }
    }
    if ($matchtype >= 1 && $matchtype < 3){
       $matchtype++;
       $matchtype = "$matchtype";
       $punteggio1 = 20;
       $punteggio2 = 40;
      }
    elseif ($matchtype===3) {
      $matchtype="LAST_16";
      $punteggio1 = 30;
      $punteggio2 = 60;
      }
    elseif ($matchtype==="LAST_16") {
      $matchtype="QUARTER_FINALS";
      $punteggio1 = 45;
      $punteggio2 = 90;
      }
    elseif ($matchtype==="QUARTER_FINALS") {
      $matchtype="SEMI_FINALS";
      $punteggio1 = 70;
      $punteggio2 = 140;
      }
    elseif ($matchtype==="SEMI_FINALS") {
      $matchtype="FINAL";
      $punteggio1 = 100;
      $punteggio2 = 200;
      }
    $sql = "UPDATE torneo SET fase='$matchtype'";
    $result = sqlquery($sql);
    foreach($files as $filesname) {
      $i=0;
      $y=0;
      $file= $filename[$y];
      $sql = "SELECT punti FROM users WHERE username= '$file'";
      $result = sqlquery($sql);
      while($row = $result->fetch_assoc()) {
      $punti=$row["punti"];
      }
      foreach($matches->matches as $match){
        $risultato = $match->score->fullTime->homeTeam ."-".$match->score->fullTime->awayTeam;
        $decode =json_decode($filesname,true);
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
}
function matchtype(){
  $sql = "SELECT fase FROM torneo";
  $result= sqlquery($sql);
  $ris = $result->fetch_assoc();
  $matchtype = $ris["fase"];
  $status=1;
  while ($status != 0) {
    if (!is_string($matchtype)){
    $uri = 'http://api.football-data.org/v2/competitions/EC/matches/?matchday='.$matchtype;
    $reqPrefs['http']['method'] = 'GET';
    $reqPrefs['http']['header'] = 'X-Auth-Token: 44623b1a626048ed8afd8e884d394e53';
    $stream_context = stream_context_create($reqPrefs);
    $response = file_get_contents($uri, false, $stream_context);
    $matches = json_decode($response);
  }
  else {
    $uri = 'http://api.football-data.org/v2/competitions/EC/matches/?stage='.$matchtype;
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
    if ($matchtype >= 1 && $matchtype < 3){
      $matchtype++;
      }
    elseif ($matchtype===3) {
      $matchtype="LAST_16";
      }
    elseif ($matchtype==="LAST_16") {
      $matchtype="QUARTER_FINALS";
      }
    elseif ($matchtype==="QUARTER_FINALS") {
      $matchtype="SEMI_FINALS";
      }
    elseif ($matchtype==="SEMI_FINALS") {
      $matchtype="FINAL";
      }
    }
  }
  return $matchtype;
}
