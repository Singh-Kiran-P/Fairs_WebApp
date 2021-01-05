<?php
require '../../../server/classes/class.fair.php';
session_start();
$errorMsg = "";
if (isset($_GET['zoneId']) && isset($_GET['free_slots'])) {
  $zoneId = $_GET['zoneId'];
  $free_slots = $_GET['free_slots'];
}


if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {

  if (isset($_POST['submit_addSlot'])) {

    $zoneId = $_GET['zoneId'];
    $free_slot = $_GET['free_slots'];
    $openingSlot = $_POST['openingSlot'];
    $closingSlot = $_POST['closingSlot'];

    $fair = new Fair();
    $Msg = $fair->checkingZoneSlot($zoneId, $openingSlot, $closingSlot);
    if ($Msg == '') {
      $fairModel = $fair->getFairModel($_SESSION['fairId']);

      $zones = $fair->getAllZones($_SESSION['fairId']);
      foreach ($zones as $z) {
        $Msg = $fair->addZoneSlot($z['zone_id'], $openingSlot, $closingSlot, $z['freeSlots'], $fairModel->getVar());
      }
    }
  }
  if (isset($_POST['submit'])) {
    header("Location: listOfFair.php");
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/addZone.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add ZoneSlot</title>
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?zoneId=<?php echo $zoneId ?>&free_slots=<?php echo $free_slots ?>" method="post" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>

    <div class="mainCol1 g">
      <center>

        <h1 class="topTitle">Add Zone timeslots <?php if (isset($_GET['fairId'])) echo "to " . $_GET['fairId']  ?></h1>


        <!-- time slots -->
        <div>
          <div class="sidebyside">
            <input type="text" name="openingSlot" placeholder="Opening Slot" onfocus="(this.type='time')" onblur="(this.type='text')">
            <input type="text" name="closingSlot" placeholder="Closing Slot" onfocus="(this.type='time')" onblur="(this.type='text')">
          </div>
        </div>
        <button type="submit" name="submit_addSlot" class="addSlot">Add Slot</button>


        <p id="error">
          <?php
          if (isset($_POST['submit_addSlot'])) {
            echo $Msg;
          }
          ?>
        </p>

      </center>

    </div>

    <button type="submit" name="submit" id="btn">Save</button>

  </form>

</body>
<!-- Script -->
<script src="addZoneSlot.js"></script>

</html>
