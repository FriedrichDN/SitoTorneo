<?php
if (isset($_POST["submit"])){

  $username = $_POST["username"];
  $password = $_POST["password"];
  $confirm_password = $_POST["confirm_password"];

  require_once $_SERVER['DOCUMENT_ROOT']."/script/config.php";
  require_once $_SERVER['DOCUMENT_ROOT']."/script/functions.php";

  if (emptyInputRegister($username,$password,$confirm_password) !== false){
    header ("location: ../register.php?error=emptyinput");
    exit();
  }
  if (invalidUsername($username) !== false){
    header ("location: ../register.php?error=invalidusername");
    exit();
  }
  /*    if (invalidPassword($passowrd) !== false){
  header ("location: ../register.php?error=invalidpassword");
  exit();
}
if (notEqualPassword($password,$confirm_password) !== false){
header ("location: ../register.php?error=notequalpassword");
exit();
}
*/
if (usernameExist($connection,$username) !== false){
  header ("location: ../register.php?error=usernametaken");
  exit();
}

createUser($connection,$username,$password);
}

else {
  header ("location: ../register.php");
  exit();
}
