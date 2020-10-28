<?php
require '../../../server/classes/class.fair.php';

Fair::add();

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
  <form action="" method="post" class="content" id="form">

    <div class="mainCol1 g">
      <center>

        <h1 class="topTitle">Add Fair</h1>

        <input type="text" name="title" placeholder="Title">

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required></textarea>

        <input type="file" name="file[]" class="inputfile" multiple>

        <div class="datum">
          <input type="date" name="startDate" placeholder="Start Date">
          <input type="date" name="endDate" placeholder="End Date">
        </div>

        <div class="datum">
          <input type="time" name="openingHour" placeholder="Opening Hour">
          <input type="time" name="closingHour" placeholder="Closing Hour">
        </div>

        <input type="text" name="location" placeholder="Location">

      </center>
sqdqsd

    </div>

    <button type="submit" id="btn">Save</button>

  </form>
  <!-- print msg on save -->
  <?php

  ?>
</body>

</html>
