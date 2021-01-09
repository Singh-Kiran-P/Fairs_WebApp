<?php
include_once '../server/classes/class.fair.php';
include_once '../server/classes/class.upload.php';
$fair = new Fair();
$outHTML = '';
// FairModels
$fairRow = $fair->getAllFairs();
if ($fairRow != null) {
  $n = 0;
  foreach ($fairRow as $f) {
    if ($n < 2) {
      $fairInfo = $f->getVar();

      $fairId = $fairInfo['fair_id'];
      $title = $fairInfo['title'];
      $desc = $fairInfo['description'];
      $start_date = $fairInfo['start_date'];
      $end_date = $fairInfo['end_date'];
      $opening_hour = $fairInfo['opening_hour'];
      $closing_hour = $fairInfo['closing_hour'];
      $location = $fairInfo['location'];
      $imgCount = $fairInfo['tot_Img'];
      // process Img
      $outHTML_Img = "";
      if ($fairRow != null) {
        for ($i = 0; $i < $imgCount; $i++) {
          $toSearchFile = $fairId . "_" . $i;
          $fairImg = Upload::getUploadedFilePath($toSearchFile, "fair_img");
          $outHTML_Img .= "<img alt='fair_images' src='../server/uploads/fair_img/" . $fairImg . "'></img>";
        }
      }

      $outHTML .= '<div class="info striped-border">';
      $outHTML .= '<h2>' . $title . '</h2>';
      $outHTML .= '<div class="img">' . $outHTML_Img . '</div>';
      $outHTML .= '<p>';
      $outHTML .= $desc;
      $outHTML .= '</p>';
      $outHTML .= '</div>';
      $n++;
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/index.css">
  <!-- favicon -->
  <?php include "./favicon/favicon.php"; ?>
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "index";
    include './componets/navbarTop.php';
    ?>
  </header>

  <!-- Banner foto -->
  <div class="banner">
    <img src="/~kiransingh/project/static/img/banner_Van_hasselt.be.png" alt="">
  </div>

  <!-- The flexible grid (content) -->
  <div class="content">
    <div class="main">
      <?php echo $outHTML; ?>
    </div>
  </div>
</body>

<!-- Footer -->
<?php include './componets/footer.php'; ?>

</html>
