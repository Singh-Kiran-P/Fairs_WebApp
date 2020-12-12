<?php
require '../../../server/classes/class.city.php';
require '../../../server/classes/class.accounts.php';

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
    $outHTML_desc = '<p id="short_desc">' . $showDesc . '<span id="dots">...</span><span id="more">' . $moreDesc . '</span></p>';


  $telephone = $account->getTelephone();
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Profile</title>
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
        echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile_img/$userId.jpg' alt='Profile foto'>"
        ?>
      </div>
      <center>
        Name
        <input type="text" placeholder="Name" value="<?php echo $name; ?>" disabled>
        Email
        <input type="text" placeholder="Email" value="<?php echo $email; ?>" disabled>
        Type
        <input type="text" placeholder="Type" value="<?php echo $type; ?>" disabled>
        Telephone Number
        <input type="text" placeholder="Telephone" value="<?php echo $telephone; ?>" disabled>
        Username
        <input type="text" placeholder="Username" value="<?php echo $username; ?>" disabled>
        Description
        <div class="desc">
          <?php echo $outHTML_desc; ?>
          <button id="btn_More">Read more</button>
        </div>
      </center>
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
