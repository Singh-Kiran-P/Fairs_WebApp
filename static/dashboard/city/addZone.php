<?php
require '../../../server/classes/class.fair.php';
require '../../../server/classes/class.upload.php';
session_start();
$errorMsg = "";
$tileFair = "";
if (isset($_GET['fairId']) && isset($_GET['title'])) {
  $_SESSION['fairId'] = $_GET['fairId'];
  $tileFair = $_GET['title'];
}

if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {

  if (isset($_POST['submit'])) {

    $fairId = $_SESSION['fairId'];
    $title = $_POST['title'];
    $open_spots = $_POST['open_spots'];

    $desc = $_POST['desc'];
    $location = $_POST['location'];
    $attractions = $_POST['attractions'];


    $files = $_FILES['file'];
    $video = $_FILES['video'];


    $fair = new Fair();
    $errorMsgZone = $fair->checkingAddZone($fairId, $title, $desc, $open_spots, $location, $attractions);
    $errorMsgUploadImg = Upload::checkFilesImg($files, false);
    $errorMsgUploadVideo = Upload::checkFilesVideo($video);
    if ($errorMsgZone == "" && $errorMsgUploadImg['msg'] == "" && $errorMsgUploadVideo['msg'] == "") {

      $zoneId = $fair->addZone($fairId, $title, $desc, $open_spots, $location, $attractions, 0, 0);
      $i = Upload::uploadFiles($files, $zoneId, "zone", "img");
      $v = Upload::uploadFiles($video, $zoneId, "zone", "video");
      if (is_numeric($zoneId)) {
        $fair->updateDbFileCount($zoneId, $i, $v, "zones");
        $errorMsg .= 'Zone is added successfully';

        //reset form
        $title = "";
        $open_spots = "";

        $desc = "";
        $location = "";
        $attractions = "";
      } else {
        $errorMsg .= $zoneId;
      }
    } else {
      $errorMsg = $errorMsgZone;
      if ($errorMsgUploadImg['msg'] != '')
        $errorMsg .= '<br><br>Images error :' . $errorMsgUploadImg['msg'];

      if ($errorMsgUploadVideo['msg'] != '')
        $errorMsg .= '<br><br>Video error : ' . $errorMsgUploadVideo['msg'];
    }
  }
  if (isset($_POST['submit_Save'])) {
    header("Location: addZoneSlot.php?zoneId=" .  $_GET['zoneId'] . "&free_slots=" . $_GET['open_spots']);
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?zoneId=<?php if (isset($zoneId)) echo $zoneId ?>&open_spots=<?php if (isset($_POST['open_spots'])) echo $_POST['open_spots'] ?>&fairId=<?php if (isset($_SESSION['fairId'])) echo $_SESSION['fairId'] ?>" method="post" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>

    <div class="mainCol1 g">
      <center>

        <h1 class="topTitle">Add Zone <?php echo $tileFair;  ?></h1>

        <input type="text" name="title" placeholder="Title" value="<?php if (isset($title)) echo $title; ?>" required>

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required><?php if (isset($desc)) echo $desc; ?></textarea>
        <textarea type="" name="attractions" placeholder="Give the attaction on this Zone like ->  attraction1,attraction2 " form="form" required><?php if (isset($attractions)) echo $attractions; ?></textarea>

        <div>
          <div class="sidebyside">
            <input type="number" min="1" name="open_spots" placeholder="Total free spots" value="<?php if (isset($open_spots)) echo $open_spots; ?>" required>

            <input type="text" name="location" placeholder="Location" value="<?php if (isset($location)) echo $location; ?>" required>
          </div>
        </div>

        <!-- upload files -->
        Images: <h5> (only JPG, JPEG, PNG & GIF files are allowed Max 5mb )</h5>


        <input type="file" name="file[]" class="inputfile" value="" multiple>

        Video's: <h6> (only MP4 and MK files are allowed Max 20mb )</h6>

        <input type="file" name="video[]" class="inputfile" value="" multiple>



        <p id="error">
          <?php
          if (isset($_POST['submit'])) {
            echo $errorMsg;
          }
          ?>
        </p>

      </center>
      <button type="submit" name="submit" id="btn">Add</button>


    </div>

    <?php echo 'addZoneSlot.php?zoneId="' .  $_GET["zoneId"] . '"&free_slots="' . $_GET["open_spots"]; ?>
    <button type="button" onclick="location.href=''">Next</button>


    <!-- <button type="button" name="submit_Save" id="btn"><a href="http://">Next</a> Next</button> -->

  </form>

</body>


<!-- Script -->
<script src="addZone.js"></script>

</html>
