<!DOCTYPE html>
<html lang="en">

<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/index.css">
  <!-- favicon -->
  <?php include "./favicon/favicon.php"; ?>
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "index";
    include './componets/navbarTop.php';
    ?>
  </header>

  <!-- Banner foto -->
  <div class="banner">
    <img src="/~kiransingh/project/static/img/banner_Van_hasselt.be.png" alt="">
  </div>

  <!-- The flexible grid (content) -->
  <div class="content">
    <div class="main">
      <div class="info striped-border">
        <h2>PROJECT INFO</h2>
        <div class="fakeimg">Image</div>
        <p>Some text..</p>
        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
          ullamco.
        </p>
      </div>
      <div class="info striped-border">
        <h2>PROJECT INFO</h2>
        <div class="fakeimg">Image</div>
        <p>Some text..</p>
        <p>Sunt in culpa qui officia deserunt mollit anim id est laborum consectetur adipiscing elit, sed do eiusmod
          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
          ullamco.
        </p>
      </div>
    </div>
  </div>

</body>

<!-- Footer -->
<?php include './componets/footer.php'; ?>

</html>
