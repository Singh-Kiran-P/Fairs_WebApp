<?php
require '../../../server/classes/class.city.php';
require '../../../server/classes/class.accounts.php';

session_start();

if (isset($_SESSION['loggedin'])) {
  $userId = $_SESSION['userId'];
  $type = $_SESSION['type'];
  $account = new Accounts();
  $account->init($userId, $type);

  $username = $account->getUsername();
  $email = $account->getEmail();
  $type = $account->getType();
  $name = $account->getName();

  $desc = $account->getShort_desc();
  $telephone = $account->getTelephone();
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
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/profile.css">
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "city_nav";
    include '../../componets/navbarTop.php';
    ?>
  </header>



  <!-- The flexible grid (content) -->
  <div class="content">
    <div class="mainCol1 g">
      <!-- Profile foto -->
      <div class="profileImg">
        <?php
        echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile_img/$userId.jpg' alt=''>"
        ?>
      </div>
      <center>
        Name
        <input type="text" placeholder="Name" value="<?php echo $name; ?>" disabled>
        Email
        <input type="text" placeholder="Email" value="<?php echo $email; ?>">
        Type
        <input type="text" placeholder="Type" value="<?php echo $type; ?>" disabled>
        Telephone Number
        <input type="text" placeholder="Telephone" value="<?php echo $telephone; ?>">
        Username
        <input type="text" placeholder="Username" value="<?php echo $username; ?>">
      </center>
    </div>

  </div>

</body>

</html>
