<?php
include_once '../../../server/classes/class.fair.php';
include_once '../../../server/classes/class.upload.php';
include_once '../../../server/classes/class.searchFair.php';
include_once '../../../server/classes/class.model.fair.php';
session_start();


if (isset($_SESSION['loggedin'])) {
  $fairId = $_GET['fair_id'];
  $fairmodel = new Fair();
  $search = new SearchFair();

  $imgCount = $search->totCountFiles($fairId, "fair");


  // $listOfFairs is a array of FairModels
  $fairRow = $fairmodel->getFairModel($fairId);

  $outHTML = "";
  if ($fairRow != null) {
    $s = $fairRow->getVar();
    for ($i = 0; $i < $imgCount; $i++) {
      $toSearchFile = $fairId . "_" . $i;
      $profilePic = Upload::getUploadedFilePath($toSearchFile,"fair_img");
      $outHTML .= "<img width='300'  alt='fair images' src='../../../server/uploads/fair_img/".$profilePic."'></img>";
    }
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
      <?php echo $outHTML; ?>
    </center>
  </div>

</body>

</html>
