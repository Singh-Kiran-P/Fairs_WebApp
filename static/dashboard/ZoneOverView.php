<?php
include_once '../../server/classes/class.fair.php';
include_once '../../server/classes/class.upload.php';
include_once '../../server/classes/class.searchFair.php';
include_once '../../server/classes/class.model.fair.php';
session_start();
if (isset($_SESSION['loggedin'])) {
  if (isset($_GET['zoneId'])) {
    $zoneId = $_GET['zoneId'];
    $fair = new Fair();
    $zone = $fair->getZone($zoneId);

    // process Img
    $outHTML_Img = "";
    if ($zone != null) {
      for ($i = 0; $i < $zone['totimg']; $i++) {
        $toSearchFile = $zoneId . "_" . $i;
        $img = Upload::getUploadedFilePath($toSearchFile, "zone_img");
        $outHTML_Img .= "<img alt='fair images' src='../../server/uploads/zone_img/" . $img . "'></img>";
      }
    }

    // process video
    $outHTML_Video = '<div class="video">';

    $outHTML_Video .= '<video width="320" height="240" controls>';
    if ($zone != null) {
      for ($i = 0; $i < $zone['totvideo']; $i++) {
        $toSearchFile = $zoneId . "_" . $i;
        $video = Upload::getUploadedFilePath($toSearchFile, "zone_video");
        $ext = explode(".", $video);
        $outHTML_Video .= "<source  src='../../server/uploads/zone_video/" . $video . "' type='video/" . $ext[1] . "'>";
      }
    }
    $outHTML_Video .= '</video></div>';
    if ($zone['totvideo'] == 0)
      $outHTML_Video = '';

    //process info
    $outHTML_Info = '';
    $outHTML_Info .=   'Title';
    $outHTML_Info .=   '<input type="text" placeholder="Name" value="' . $zone['title'] . '" disabled>';
    $outHTML_Info .=   'Attractions';
    $outHTML_Info .=   '<input type="text" placeholder="Type" value="' . $zone['attractions'] . '" disabled>';
    $outHTML_Info .=   'Location';
    $outHTML_Info .=   ' <input type="text" placeholder="Username" value="' . $zone['location'] . '" disabled>';
    $outHTML_Info .=   ' <input type="hidden" placeholder="Username" value="' . $zone['zoneId'] . '" name="zoneId" >';
    $outHTML_Info .=   'Description';


    //show more information
    $desc =  $zone['description'];
    $showDesc = substr($desc, 0, (strlen($desc) - 1) * (1 / 3));
    $moreDesc = substr($desc, (strlen($desc) - 1) * (1 / 3) + 1, (strlen($desc) - 1));
    $outHTML_desc = '';
    //check if desc not null
    if (strlen($desc) != 0)
      $outHTML_desc = '<p id="short_desc">' . $showDesc . '<span id="dots">...</span><span id="more">' . $moreDesc . '</span></p>';

    // Add list of dates
    $dateSlectorHTML = "";
    $dates = $fair->getZonesDate($zoneId);
    if (count($dates) > 0) {
      foreach ($dates as $d) {
        $date = '<option value="' . $d . '">' . $d . '</option>';
        $dateSlectorHTML .= $date;
      }
    }

    $zoneInfoTableHeading =
      "<tr><th>Date</th><th>Start time</th><th>End time</th><th>Open</th></tr>";
    $zoneInfoTableHeading .= $fair->showZoneTimeSlots($zoneId);
  }
} else {
  header("Location: ../unauthorized.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/fairView.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Fair</title>
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

  <!-- reservation btn for the visitor -->
  <?php if ($_SESSION['type'] == 'visitor') {
    $outHTML = '<form action="visitor/reservation.php" method="post" class="content">';
    $outHTML .= '<input type="hidden" name="zoneId" value="' . $_GET['zoneId'] . '">';
    $outHTML .= '<input type="hidden" name="fairId" value="' . $_GET['fairId'] . '">';
    $outHTML .= '<button type="submit">Book Tickets</button>';
    $outHTML .= '</form>';
    echo $outHTML;
  }
  ?>

  <!-- The flexible grid (content) -->
  <div class="content">
    <div class="Img">
      <center>
        <?php echo $outHTML_Img; ?>
      </center>
    </div>

    <!-- Zone info -->
    <div class="infoZone">

      <center>
        <?php echo $outHTML_Info ?>
        <div class="desc">
          <?php echo $outHTML_desc; ?>
          <button id="btn_More">Read more</button>
        </div>

        <!-- Zone Time slots info -->
        <?php if ($_SESSION['type'] == 'city') {
          //drop box Dates
          echo '<select name="date" class="dropBox_Dates" onchange="showTimeSlotByDate(this.value)">';
          echo '<option value="">Select a Date:</option>';
          echo $dateSlectorHTML;
          echo '</select>';
        }
        ?>
      </center>

    </div>

    <!-- show video -->
    <?php echo $outHTML_Video; ?>
    <!-- Zone Time slots info -->
    <?php if ($_SESSION['type'] == 'city') {
      echo '<table class="zoneTimeslotstable">';
      echo $zoneInfoTableHeading;
      echo '</table>';
    }
    ?>




    <!-- Error msg -->
    <p id="error"><?php if (isset($_POST['submit'])) echo $errorMsg; ?></p>



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

<!-- Script -->
<script src="ZoneOverView.js"></script>
<script src="ShowMore.js"></script>


</html>
