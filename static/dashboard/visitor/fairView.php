<?php
include_once '../../../server/classes/class.fair.php';
include_once '../../../server/classes/class.model.fair.php';
session_start();


if (isset($_SESSION['loggedin'])) {
  $fairId = $_GET['fairId'];
  $fairmodel = new Fair();

  // $listOfFairs is a array of FairModels
  $fairRow = $fairmodel->getFairModel($fairId);

  if ($fairRow != null) {

    $html_List_of_Faris = "";
  }
} else {
  header("Location: ../unauthorized.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/fairView.css">
  <title>List Of Fairs</title>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "profile";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <div class="content">
    <center>
      <p>fqsdfqsf = sdsqf</p>
      <p>fqsdfqsf = sdsqf</p>
      <p>fqsdfqsf = sdsqf</p>
      <p>fqsdfqsf = sdsqf</p>
    </center>
  </div>

</body>

</html>
