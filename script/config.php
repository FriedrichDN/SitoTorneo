<?php
$servername = "localhost";
$DBUsername = "root";
$DBPassword = "St&ll!n@37";
$DBName = "my_torneo";

$connection = mysqli_connect($servername,$DBUsername,$DBPassword,$DBName);

if (!connection){
  die("Connessione al database fallita: " . mysqli_connect_error());
}
