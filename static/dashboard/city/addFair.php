<?php
require '../../../server/classes/class.fair.php';
require '../../../server/classes/class.upload.php';
session_start();

if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {

  if (isset($_POST['submit'])) {
    $cityId = $_SESSION['cityId'];

    $title = $_POST['title'];
    $desc = $_POST['desc'];
    $files = $_FILES['file'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $openingHour = $_POST['openingHour'];
    $closingHour = $_POST['closingHour'];
    $location = $_POST['location'];

    $fair = new Fair();
    $errorMsg = $fair->checkingAddFair($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location);
    if ($errorMsg == "") {

      //check files for uploading
      $error = Upload::checkFilesImg($files, false);
      if ($error['msg'] != '') //error while checking
        $errorMsg = $error['msg'];
      else {
        $fairId = $fair->addFair($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location, 0);
        $i = Upload::uploadFiles($files, $fairId, "fair", "img");
        $fair->updateDbFileCount($fairId, $i, 0, "fair");
        header("Location: addZone.php?fairId=" . $fairId . "&title=" . $title);
      }
    }
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/city_forms.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Fair</title>
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>

    <div class="mainCol1 g">
      <center>

        <h1 class="topTitle">Add Fair</h1>

        <input type="text" name="title" placeholder="Title" value="<?php if (isset($_POST['title'])) echo _e($_POST['title']); ?>" required>

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required><?php if (isset($_POST['desc'])) echo _e($_POST['desc']); ?></textarea>

        <h5> (only JPG, JPEG, PNG & GIF files are allowed Max 5mb )</h5>
        <label for="files">Images:</label>
        <input type="file" name="file[]" class="inputfile" value="<?php if (isset($_POST['file'])) echo _e($_POST['file']); ?>" multiple>
        <div>
          <div class="sidebyside">

            <label for="startDate">Start Date:</label>
            <input type="date" name="startDate" placeholder="Start Date" min="<?php echo date('Y-m') . '-' . (date('d') + 1); ?>" value="<?php if (isset($_POST['startDate'])) echo _e($_POST['startDate']); ?>" required>

            <label for="endDate">End Date:</label>
            <input type="date" name="endDate" min="<?php echo date('Y-m') . '-' . (date('d') + 1); ?>" placeholder="End Date" value="<?php if (isset($_POST['endDate'])) echo _e($_POST['endDate']); ?>" required>

            <label for="openingHour">Opening Hour:</label>
            <input type="time" name="openingHour" placeholder="Opening Hour" value="<?php if (isset($_POST['openingHour'])) echo _e($_POST['openingHour']); ?>" required>

            <label for="closingHour">Closing Hour:</label>
            <input type="time" name="closingHour" placeholder="Closing Hour" value="<?php if (isset($_POST['closingHour'])) echo _e($_POST['closingHour']); ?>" required>
          </div>

        </div>
        <label for="location">Location:</label>
        <input type="text" name="location" placeholder="Location" value="<?php if (isset($_POST['location'])) echo _e($_POST['location']); ?>" required>

        <p id="error">
          <?php
          if (isset($_POST['submit'])) {
            echo _e($errorMsg);
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
