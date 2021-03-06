<?php
require '../../../server/classes/class.fair.php';
require '../../../server/classes/class.upload.php';
session_start();
$errorMsg = "";
$tileFair = "";
$enabled = "";
$disabled = "disabled";
$outHTML_SAVE = "";
if (isset($_GET['fairId']) && isset($_GET['title'])) {
  $_SESSION['fairId'] = $_GET['fairId'];
}

if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {
  $fair = new Fair();
  $fairData = $fair->getFairModel($_SESSION['fairId'])->getVar();
  $tileFair = $fairData['title'];
  $opening = $fairData['opening_hour'];
  $closing = $fairData['closing_hour'];
  if (isset($_POST['submit'])) {

    $fairId = $_SESSION['fairId'];
    $title = $_POST['title'];
    $open_spots = $_POST['open_spots'];

    $desc = $_POST['desc'];
    $location = $_POST['location'];
    $attractions = $_POST['attractions'];


    $files = $_FILES['file'];
    $video = $_FILES['video'];


    $errorMsgZone = $fair->checkingAddZone($fairId, $title, $desc, $open_spots, $location, $attractions);
    $errorMsgUploadImg = Upload::checkFilesImg($files, false);
    $errorMsgUploadVideo = Upload::checkFilesVideo($video);
    if ($errorMsgZone == "" && $errorMsgUploadImg['msg'] == "" && $errorMsgUploadVideo['msg'] == "") {

      $zoneId = $fair->addZone($fairId, $title, $desc, $open_spots, $location, $attractions, 0, 0);
      $i = Upload::uploadFiles($files, $zoneId, "zone", "img");
      $v = Upload::uploadFiles($video, $zoneId, "zone", "video");
      if (is_numeric($zoneId)) {
        $_SESSION['zoneId'] = $zoneId;
        $fair->updateDbFileCount($zoneId, $i, $v, "zones");
        $errorMsg .= 'Zone is added successfully';
        $enabled = "disabled";
      } else {
        $errorMsg .= $zoneId;
      }
    } else {
      $errorMsg = $errorMsgZone;
      if ($errorMsgUploadImg['msg'] != '')
        $errorMsg .= '<br><br>Images error :' . $errorMsgUploadImg['msg'];

      if ($errorMsgUploadVideo['msg'] != '')
        $errorMsg .= '<br><br>Video error :' . $errorMsgUploadVideo['msg'];
    }
  }

  if (isset($_POST['submit_slot'])) {
    $zoneId =  $_SESSION['zoneId'];
    $openingSlot = $_POST['openingSlot'];
    $closingSlot = $_POST['closingSlot'];

    $fair = new Fair();
    $zoneData = $fair->getZone($zoneId);
    $title = $zoneData['title'];
    $open_spots = $zoneData['open_spots'];

    $desc = $zoneData['description'];
    $location = $zoneData['location'];
    $attractions = $zoneData['attractions'];



    $Msg = $fair->checkingZoneSlot($zoneId, $openingSlot, $closingSlot);
    if ($Msg == '') {
      $fairModel = $fair->getFairModel($_SESSION['fairId']);

      $zones = $fair->getAllZones($_SESSION['fairId']);
      foreach ($zones as $z) {
        $Msg = $fair->addZoneSlot($z['zone_id'], $openingSlot, $closingSlot, $z['freeSlots'], $fairModel->getVar());
      }
      $errorMsg .= '<br>' . "Zone Slot added successfully.";
      $outHTML_SAVE .= '<div id="btn"><a  id="btn" href="addZone.php">Add new Zone</a></div>';
      $outHTML_SAVE .= '<div id="btn"><a  id="btn" href="listOfFair.php">All Fairs</a></div>';
    } else
      $errorMsg .= '<br>' . $Msg;

    $enabled = "disabled";
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
  <title>Add Zone</title>
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="content" id="form" onsubmit="return <?php if ($enabled == '') echo 'validateFormZone()';
                                                                                                                                else echo 'validateFormSlots()' ?>" enctype='multipart/form-data'>

    <div class="mainCol1 g">
      <center>

        <h1 class="topTitle"><?php if ($enabled != "") echo "Add Slot To " . $title;
                              else echo 'Add Zone ' .  $tileFair; ?></h1>

        <input type="text" name="title" placeholder="Title" value="<?php if (isset($title)) echo $title; ?>" required <?php echo $enabled; ?>>

        <textarea type="" name="desc" cols="50" rows="10" placeholder="Give a short discription about this fair" form="form" required <?php echo $enabled; ?>><?php if (isset($desc)) echo $desc; ?></textarea>

        <textarea type="" name="attractions" cols="50" rows="10" placeholder="Give the attaction on this Zone like ->  attraction1,attraction2 " form="form" required <?php echo $enabled; ?>><?php if (isset($attractions)) echo $attractions; ?></textarea>

        <div>
          <div class="sidebyside">
            <input type="number" min="1" name="open_spots" placeholder="Total free spots" value="<?php if (isset($open_spots)) echo $open_spots; ?>" required <?php echo $enabled; ?>>

            <input type="text" name="location" placeholder="Location" value="<?php if (isset($location)) echo $location; ?>" required <?php echo $enabled; ?>>
          </div>
        </div>

        <!-- upload files -->
        Images: <h5> (only JPG, JPEG, PNG & GIF files are allowed Max 5mb )</h5>


        <input type="file" name="file[]" class="inputfile" value="" multiple <?php echo $enabled; ?>>

        Video's: <h6> (only MP4 and MK files are allowed Max 20mb )</h6>

        <input type="file" name="video[]" class="inputfile" value="" multiple <?php echo $enabled; ?>>

        <!--Add Zone -->
        <?php if ($enabled == "") echo '<button type="submit" name="submit" id="btn">Add Zone</button>'; ?>

        <!-- time slots -->
        <div <?php if ($enabled == "") echo "class='hidden'"; ?>>
          <p>Time slots: <?php echo " between " . _e($opening) . " - " . _e($closing) ?></p>
          <div class="sidebyside">
            <label for="openingSlot">Opening Slot:</label>
            <input type="time" name="openingSlot" placeholder="Opening Slot" value="<?php if (isset($openingSlot)) echo $openingSlot; ?>">
            <label for="closingSlot">Closing Slot:</label>
            <input type="time" name="closingSlot" placeholder="Closing Slot" value="<?php if (isset($closingSlot)) echo $closingSlot; ?>">
          </div>
          <button type="submit" name="submit_slot" id="btn">Add Slot</button>
        </div>

        <?php echo $outHTML_SAVE ?>

        <p id="error">
          <?php
          if ($errorMsg != "") {
            echo $errorMsg;
          }
          ?>
        </p>

      </center>




    </div>




  </form>

</body>


<!-- Script -->
<script src="addZone.js"></script>

</html>
