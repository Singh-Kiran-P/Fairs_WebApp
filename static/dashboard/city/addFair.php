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

        <input type="text" name="title" placeholder="Title" value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>" required>

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required><?php if (isset($_POST['desc'])) echo $_POST['desc']; ?></textarea>

        <h5> (only JPG, JPEG, PNG & GIF files are allowed Max 5mb )</h5>
        <input type="file" name="file[]" class="inputfile" value="<?php if (isset($_POST['file'])) echo $_POST['file']; ?>" multiple>
        <div>
          <div class="sidebyside">
            <input type="text" name="startDate" placeholder="Start Date" min="<?php echo date('Y-m') . (date('d') + 1) ?>" onfocus="(this.type='date')" onblur="(this.type='text')" value="<?php if (isset($_POST['startDate'])) echo $_POST['startDate']; ?>" required>
            <input type="text" name="openingHour" placeholder="Opening Hour" onfocus="(this.type='time')" onblur="(this.type='text')" value="<?php if (isset($_POST['openingHour'])) echo $_POST['openingHour']; ?>" required>
            <input type="text" name="endDate" placeholder="End Date" onfocus="(this.type='date')" onblur="(this.type='text')" value="<?php if (isset($_POST['endDate'])) echo $_POST['endDate']; ?>" required>
            <input type="text" name="closingHour" placeholder="Closing Hour" onfocus="(this.type='time')" onblur="(this.type='text')" value="<?php if (isset($_POST['closingHour'])) echo $_POST['closingHour']; ?>" required>
          </div>

        </div>
        <input type="text" name="location" placeholder="Location" value="<?php if (isset($_POST['location'])) echo $_POST['location']; ?>" required>

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
