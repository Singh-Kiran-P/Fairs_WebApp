<?php

require '../../../server/classes/class.review.php';
require '../../../server/classes/class.fair.php';

session_start();
if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor")) {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


$msg = "";
$review = new Review();

if (isset($_GET['reservationId'])) {
  $info = $review->getInfoForReviewPage($_GET['reservationId']);
}

if (isset($_POST['review']) && isset($_POST['rating'])) {
  $msg = $review->writeReview($info['zoneId'], $_SESSION['userId'], $_POST['review'], $_POST['rating']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/review.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Review</title>
  <!-- favicon -->
  <?php include "../../favicon/favicon.php"; ?>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "review";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <!-- The flexible grid (content) -->
  <div class=content>
    <div class="review">
      <center>
        <h2> Review for zone: <?php if (isset($info)) echo _e($info['zoneTitle']); ?></h2>
      </center>
      <div class="info">
        Info:
        <ul>
          <li>Fair Title:&nbsp <?php if (isset($info)) echo _e($info['fairTitle']); ?></li>
          <li>Zone Title:&nbsp <?php if (isset($info)) echo _e($info['zoneTitle']); ?></li>
        </ul>

        Write your review:

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?reservationId=<?php echo $_GET['reservationId']; ?>" method="post" class="reviewFrom">
          <textarea name="review" id="" cols="30" rows="10" required></textarea>
          <div>
            Rating : <input type="number" min="1" max="5" name="rating" value="1" required>
            <button type="submit" name=""><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
          </div>
        </form>
        <!-- Error msg -->
        <?php
        if ($msg != "") {
          echo _e($msg);
        }
        ?>
      </div>
    </div>
  </div>

</body>
<!-- Script -->
<script src="searchFairByName.js"></script>

</html>
