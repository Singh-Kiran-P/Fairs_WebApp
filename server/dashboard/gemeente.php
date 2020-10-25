<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
  <H1>GEMEENTE</H1>
<body>

</body>

</html>

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

  $s = '<a href="'.$rootURL."/~kiransingh/project/server/auth/logout.php" .'">Logout</a>';
  echo $s;

  $s = '<img  height="100" src="'.$rootURL."/~kiransingh/project/server/uploads/profileImg/user_". $_SESSION['username'] .'.jpg"></img>';
  echo $s;
}
else{
  echo "login first";
}
