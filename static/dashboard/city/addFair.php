<?php
require '../../../server/classes/class.fair.php';
session_start();

if (isset($_SESSION['loggedin'])) {
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

    $fairId = Fair::add($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location, count($files));
    Fair::uploadFiles($files, $fairId);

    header("Location: listOfFair.php");
  }
} else {
  header("Location: ../unauthorized.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/AddFair.css">
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

        <input type="text" name="title" placeholder="Title" required>

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required></textarea>

        <input type="file" name="file[]" class="inputfile" multiple>
        <div>
          <div class="date">
            <input type="text" name="startDate" placeholder="Start Date" onfocus="(this.type='date')" onblur="(this.type='text')" required>
            <input type="text" name="openingHour" placeholder="Opening Hour" onfocus="(this.type='time')" onblur="(this.type='text')" required>
            <input type="text" name="endDate" placeholder="End Date" onfocus="(this.type='date')" onblur="(this.type='text')" required>
            <input type="text" name="closingHour" placeholder="Closing Hour" onfocus="(this.type='time')" onblur="(this.type='text')" required>
          </div>


        </div>
        <input type="text" name="location" placeholder="Location" required>

        <p id="error"></p>

      </center>

    </div>

    <button type="submit" name="submit" id="btn">Save</button>

  </form>

</body>
<!-- Script -->
<script src="addFair.js"></script>

</html>
