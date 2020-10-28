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

        <input type="text" placeholder="Title">

        <textarea type="" name="desc" placeholder="Give a short discription about this fair" form="form" required></textarea>

        <input type="file" name="file[]" class="inputfile" multiple>

        <div class="datum">
          <input type="text" placeholder="Start Datum">
          <input type="text" placeholder="End Datum">
        </div>

        <div class="datum">
          <input type="text" placeholder="Opening Hour">
          <input type="text" placeholder="Closing Hour">
        </div>

        <input type="text" placeholder="Location">

      </center>
    </div>

    <button type="submit" id="btn">Save</button>
  </form>
</body>

</html>
