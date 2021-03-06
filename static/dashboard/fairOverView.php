<?php
include_once '../../server/classes/class.fair.php';
include_once '../../server/classes/class.upload.php';
include_once '../../server/classes/class.searchFair.php';
include_once '../../server/classes/class.model.fair.php';
session_start();


if (($_SESSION['type'] == "visitor" || $_SESSION['type'] == "city") && isset($_SESSION['loggedin']) && isset($_SESSION['type'])) {
  $fairId = htmlspecialchars($_GET['fair_id']);
  $fairmodel = new Fair();
  $search = new SearchFair();

  $imgCount = $search->totCountFiles($fairId, "fair");


  // FairModels
  $fairRow = $fairmodel->getFairModel($fairId);
  if ($fairRow != null) {
    $fairInfo = $fairRow->getVar();

    // process Img
    $outHTML_Img = "";
    if ($fairRow != null) {
      for ($i = 0; $i < $imgCount; $i++) {
        $toSearchFile = $fairId . "_" . $i;
        $fairImg = Upload::getUploadedFilePath($toSearchFile, "fair_img");
        $outHTML_Img .= "<img alt='fair_images' src='../../server/uploads/fair_img/" . $fairImg . "'></img>";
      }
    }

    //process Info
    $title = $fairInfo['title'];
    $desc = $fairInfo['description'];
    $start_date = $fairInfo['start_date'];
    $end_date = $fairInfo['end_date'];
    $opening_hour = $fairInfo['opening_hour'];
    $closing_hour = $fairInfo['closing_hour'];
    $location = $fairInfo['location'];

    //set zone Links
    // Add list of zones
    $zones = $fairmodel->getFairZones($fairId);
    $zoneSlectorHTML = "";
    if ($zones != null && count($zones) > 0) {
      foreach ($zones as $z) {
        $zone = '<option value="' . _e($z["zoneId"]) . '">' . _e($z["title"]) . '</option>';
        $zoneSlectorHTML .= $zone;
      }
    }

    //show more information
    $showDesc = substr($desc, 0, (strlen($desc) - 1) * (1 / 3));
    $moreDesc = substr($desc, (strlen($desc) - 1) * (1 / 3) + 1, (strlen($desc) - 1));
    $outHTML_desc = '';
    //check if desc not null
    if (strlen($desc) != 0)
      $outHTML_desc = '<p id="short_desc">' . _e($showDesc) . '<span id="dots">...</span><span id="more">' . _e($moreDesc) . '</span></p>';
  } else {
    header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/visitor/searchFairs.php');
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/fairView.css">
  <title>Fair Overview</title>
  <!-- favicon -->
  <?php include "../favicon/favicon.php"; ?>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    if ($_SESSION['type'] == 'city') {
      $typeNav = "city_nav";
      include '../componets/navbarTop.php';
    }
    if ($_SESSION['type'] == 'visitor') {
      $typeNav = "search";
      include '../componets/navbarTopVisitor.php';
    }
    ?>
  </header>

  <div class="content">
    <div class="Img">
      <center>
        <?php echo $outHTML_Img; ?>
      </center>
    </div>
    <div class="ZoneBtn">
      <!-- drop box Zones -->
      <select name="zoneId" class="slectZone" onchange="showTimeSlot(this.value)">
        <option value="">Select a Zone:</option>
        <?php echo $zoneSlectorHTML; ?>
      </select>
    </div>
    <div class="info">
      <input type="hidden" name="fairId" value="<?php echo $fairId; ?>">
      <p>Title: <?php echo $title; ?></p>
      <p> Start Date: <?php echo $start_date; ?></p>
      <p> End Date: <?php echo $end_date; ?></p>
      <p> Opening Hour: <?php echo $opening_hour; ?></p>
      <p>Closing Hour: <?php echo $closing_hour; ?></p>
      <p> Location: <?php echo $location; ?></p>
      Description:
      <div class="desc long">
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
<script src="ShowMore.js"></script>
<script src="fairOverView.js"></script>

</html>
