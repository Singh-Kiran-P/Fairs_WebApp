<?php
require '../../../server/classes/class.accounts.php';
require '../../../server/classes/class.upload.php';

session_start();


if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {

  $userId = $_SESSION['userId'];
  $type = $_SESSION['type'];
  $account = new Accounts();
  $account->init($userId, $type);

  $username = $account->getUsername();
  $email = $account->getEmail();
  $type = $account->getType();
  $name = $account->getName();

  $desc = $account->getShort_desc();
  $showDesc = substr($desc, 0, (strlen($desc) - 1) * (1 / 3));
  $moreDesc = substr($desc, (strlen($desc) - 1) * (1 / 3) + 1, (strlen($desc) - 1));
  $outHTML_desc = '';
  //check if desc not null
  if (strlen($desc) != 0)
    $outHTML_desc = '<p id="short_desc">' . _e($showDesc) . '<span id="dots">...</span><span id="more">' . _e($moreDesc) . '</span></p>';


  $telephone = $account->getTelephone();
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Profile</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/profile.css">
  <!-- favicon -->
  <?php include "../../favicon/favicon.php"; ?>
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
        $fileName = Upload::getUploadedFilePath($userId, "profile_img");
        echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile_img/$fileName' alt='Profile foto'>"
        ?>
      </div>
      <article class="info">
        <p>Name: <?php echo $name; ?></p>
        <p><?php echo $email; ?></p>
        <p>Type: <?php echo $type; ?></p>
        <p>Telephone Number: <?php echo $telephone; ?></p>
        <p>Username: <?php echo $username; ?></p>
        Description
        <div class="desc">
          <?php echo $outHTML_desc; ?>
          <button id="btn_More">Read more</button>
        </div>
      </article>
    </div>
  </div>

</body>

<!-- Linking Events -->
<script>
  var btn = document.getElementById("btn_More");
  btn.addEventListener("click", (event) => {
    showMore()
  })
</script>

<!-- Extrenal scripts -->
<script src="../ShowMore.js"></script>


</html>
