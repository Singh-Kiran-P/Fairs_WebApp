<?php
require '../../../server/classes/class.users.php';

session_start();

if (isset($_SESSION['loggedin'])) {
  $userId = $_SESSION['userId'];
  $type =  $_SESSION['type'];
  $username = $_SESSION['username'];
  $email =  $_SESSION['email'];

  /*$userObj = new Users();
  $userObj->init($userId); */
} else {
  header("Location: ../unauthorized.php");
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/gemeente_main.css">
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "gemeente_nav";
    include '../../componets/navbarTop.php';
    ?>
  </header>

  <!-- Profile foto -->
  <div class="profileImg">
    <?php
    echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile/user_$username.jpg' alt=''>"
    ?>
  </div>

  <!-- The flexible grid (content) -->
  <form action="" method="post" class="content">
    <div class="mainCol1 g">
      <center>
        <input type="text" placeholder="Name" disabled>
        <input type="text" placeholder="Email">
        <input type="text" placeholder="Type">

      </center>
    </div>
    <div class="mainCol2 b">
      <center>
        <input type="text" placeholder="Password">
        <input type="text" placeholder="Email">
        <textarea type="" name="desc" placeholder="Give a short discription of your 'gemeente'" form="usrform" required></textarea>
      </center>
    </div>

    <button type="submit" id="btn">Save</button>
  </form>

</body>

</html>
