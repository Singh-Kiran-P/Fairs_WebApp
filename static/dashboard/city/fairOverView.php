<?php
include_once '../../../server/classes/class.fair.php';
session_start();
if (isset($_SESSION['loggedin'])) {
  $fairId = $_GET['fair_id'];
  $fair = new Fair();
  $zones = $fair->getFairZones($fairId);
  $zoneSlectorHTML = "";
  $dateSlectorHTML = "";

  foreach ($zones as $z) {
    $zone = '<option value="' . $z["zoneId"] . '">' . $z["title"] . '</option>';
    $zoneSlectorHTML .= $zone;
  }

  $dates = $fair->getZonesDate($fairId);

  foreach ($dates as $d) {
    $date = '<option value="' . $d . '">' . $d . '</option>';
    $dateSlectorHTML .= $date;
  }
} else {
  header("Location: ../unauthorized.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/city_forms.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Fair</title>
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
  <div class="content w60">

    <div class="mainCol1">
      <center>
        <h1 class="topTitle">Zone timeslots of <?php echo $_GET['fair_Title']; ?> </h1>

        <div class="sidebyside">
          <select name="zoneId" onchange="showTimeSlot(this.value)">
            <option value="">Select a Zone:</option>
            <?php echo $zoneSlectorHTML; ?>
          </select>
          <select name="date" onchange="showTimeSlotByDate(this.value)">
            <option value="">Select a Date:</option>
            <?php echo $dateSlectorHTML; ?>
          </select>
        </div>
        <table class="zoneTimeslotstable">
        </table>
        <p id="error"><?php
                      if (isset($_POST['submit'])) {
                        echo $errorMsg;
                      }
                      ?></p>

      </center>
    </div>
  </div>

</body>
<!-- Script -->
<script src="fairOverView.js"></script>

</html>
