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
      <button onclick="location.href='searchFairByName.php'" id="btn">Fair name</button>
      <button onclick="location.href='searchFairByMap.php'" id="btn">Map</button>
      <button onclick="location.href='searchFairByPeriod.php'" id="btn">Period</button>

    </center>





  </div>
</body>
<!-- Script -->
<script src="addFair.js"></script>

</html>
