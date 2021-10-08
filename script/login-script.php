<?php
if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $password = $_POST["password"];

  require_once $_SERVER['DOCUMENT_ROOT']."/script/config.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/script/functions.php";

  if (emptyInputLogin($username,$password) !== false){
    header ("location: ../login.php?error=emptyinput");
    exit();
  }

  loginUser($connection,$username,$password);

}
else {
  header ("location: ../login.php");
  exit();
}
