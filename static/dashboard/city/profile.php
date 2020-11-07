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
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/city_profile.css">
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "city_nav";
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
  <form action="" method="post" class="content" id="form">
    <div class="mainCol1 g">
      <center>
        <input type="text" placeholder="Name" value="<?php echo $name; ?>" disabled>
        <input type="text" placeholder="Email" value="<?php echo $email; ?>">
        <input type="text" placeholder="Type" value="<?php echo $type; ?>" disabled>
        <input type="text" placeholder="Telephone" value="<?php echo $telephone; ?>">
      </center>
    </div>
    <div class="mainCol2 b">
      <center>
        <input type="text" placeholder="Username" value="<?php echo $username; ?>">
        <input type="text" placeholder="Password" value="">
        <textarea type="" name="desc" placeholder="Give a short discription of your city" form="form" required><?php echo $desc; ?></textarea>
      </center>
    </div>

    <button type="submit" id="btn">Save</button>
  </form>

</body>

</html>
