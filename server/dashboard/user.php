<?php
session_start();

if(isset($_SESSION['loggedin'])){


  echo "logedIn: " . $_SESSION['loggedin'];
  echo "<br>";

  echo "userId: " .$_SESSION['userId'] ;
  echo "<br>";

  echo "type: " .$_SESSION['type'] ;
  echo "<br>";

  echo "username: " .$_SESSION['username'] ;
  echo "<br>";

  echo "email: " .$_SESSION['email'] ;

  require '../config/config.php';
  echo "<br>";

  $s = '<a href="'.$rootURL."/server/auth/logout.php" .'">Logout</a>';
  echo $s;
}
else{
  echo "login first";
}
