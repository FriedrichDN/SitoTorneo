<?php
$servername = "localhost";
$DBUsername = "torneoprova";
$DBPassword = "";
$DBName = "my_torneoprova";

$connection = mysqli_connect($servername,$DBUsername,$DBPassword,$DBName);

if (!connection){
  die("Connessione al database fallita: " . mysqli_connect_error());
}
