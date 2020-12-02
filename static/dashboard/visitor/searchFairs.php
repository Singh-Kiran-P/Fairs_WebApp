<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
  header("Location: ../unauthorized.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/searchFair.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Fair</title>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "searchFair";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <!-- The flexible grid (content) -->
  <div class="content">
    <center>
      <H2>Search BY</H2>
      <a href="searchFairByName.php" id="btn">Fair name</a>
      <a href="searchFairByMap.php" id="btn">Map</a>
      <a href="searchFairByPeriod.php" id="btn">Period</a>

    </center>





  </div>
</body>
<!-- Script -->
<script src="addFair.js"></script>

</html>
