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
    $typeNav = "searchFair";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <!-- Profile foto -->
  <div class="profileImg">
    <?php
    echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile_img/$userId.jpg' alt=''>"
    ?>
  </div>

  <!-- The flexible grid (content) -->
  <form action="" method="post" class="content" id="form">
    <div class="mainCol1 g">
      <center>
        <input type="text" placeholder="Name" value="<?php echo $name; ?>" disabled>
        <input type="text" placeholder="Email" value="<?php echo $email; ?>">
        <input type="text" placeholder="Type" value="<?php echo $type; ?>" disabled>

      </center>
    </div>
    <div class="mainCol2 b">
      <center>
        <input type="text" placeholder="Username" value="<?php echo $username; ?>">
        <input type="text" placeholder="Password" value="">

      </center>
    </div>

    <button type="submit" id="btn">Save</button>
  </form>

</body>

</html>
