<?php
require '../../../server/classes/class.fair.php';
session_start();
$errorMsg = "";

if (isset($_GET['fairId']))
  $_SESSION['fairId'] = $_GET['fairId'];

if (isset($_SESSION['loggedin'])) {
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
    $errorMsg = $fair->checkingAddZone($fairId, $title, $desc, $open_spots, $location, $attractions);
    if ($errorMsg == "") {

      $zoneId = $fair->addZone($fairId, $title, $desc, $open_spots, $location, $attractions,count($files['name']),count($video['name']));
      $fair->uploadFiles($files, $zoneId, "zone","img");
      $fair->uploadFiles($video, $zoneId, "zone","video");
    }
    header("Location: addZoneSlot.php?zoneId=".$zoneId);
  }
} else {
  header("Location: ../unauthorized.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/addZone.css">
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>

    <div class="mainCol1 g">
      <center>

        <h1 class="topTitle">Add Zone <?php if (isset($_GET['fairId'])) echo "to " . $_GET['fairId']  ?></h1>

        <input type="text" name="title" placeholder="Title" value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>" required>

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required><?php if (isset($_POST['desc'])) echo $_POST['desc']; ?></textarea>
        <textarea type="" name="attractions" placeholder="Give the attaction on this Zone like ->  attraction1,attraction2 " form="form" required><?php if (isset($_POST['attractions'])) echo $_POST['attractions']; ?></textarea>

        <div>
          <div class="sidebyside">
            <input type="text" name="open_spots" placeholder="Total free spots" value="<?php if (isset($_POST['open_spots'])) echo $_POST['open_spots']; ?>" required>

            <input type="text" name="location" placeholder="Location" value="<?php if (isset($_POST['location'])) echo $_POST['location']; ?>" required>
          </div>
        </div>

        <!-- upload files -->
        <input type="file" name="file[]" class="inputfile" value="<?php if (isset($_POST['file'])) echo $_POST['file']; ?>" multiple>
        <input type="file" name="video[]" class="inputfile" value="<?php if (isset($_POST['file'])) echo $_POST['file']; ?>" multiple>

        <p id="error">
          <?php
          if (isset($_POST['submit'])) {
            echo $errorMsg;
          }
          ?>
        </p>

      </center>

    </div>



    <button type="submit" name="submit" id="btn">Save</button>

  </form>

</body>
<!-- Script -->
<script src="addFair.js"></script>

</html>
