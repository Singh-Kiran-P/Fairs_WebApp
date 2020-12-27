<?php
include_once '../../../server/classes/class.fair.php';
include_once '../../../server/classes/class.model.fair.php';
session_start();


if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {

  $cityId = $_SESSION['cityId'];
  $fair = new Fair();

  // $listOfFairs is a array of FairModels
  $listOfFairs = $fair->getListOfFairs($cityId);

  $html_List_of_Faris = "";
  if ($listOfFairs != null) {
    foreach ($listOfFairs as $fairRow) {
      $s = $fairRow->getVar();

      $html_List_of_Faris .= '
        <a href="../fairOverView.php?fair_id=' . $fairRow->getVar()['fair_id'] . '&fair_Title=' . $fairRow->getVar()['title'] . '" class="card">
        <center class="title"> <label for="Title">' . $fairRow->getVar()['title'] . '</label></center>
        <center class="title"> <label for="Location">' . $fairRow->getVar()['location'] . '</label></center>
        <div class="time">
          <div class="card_left">
            <label for="Start-Date">' . $fairRow->getVar()['start_date'] . '</label>
          </div>
          <div class="card_right">
            <label for="End-Date">' . $fairRow->getVar()['end_date'] . '</label>
          </div>
        </div>
      </a>';
    }
  } else {
    $html_List_of_Faris .= '<H4>No fairs found! Head over to Add Fair to add your own first fair!</h4>';
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/city_listOfFairs.css">
  <title>List Of Fairs</title>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "city_nav";
    include '../../componets/navbarTop.php';
    ?>
  </header>

  <div class="content">
    <?php echo $html_List_of_Faris;
    ?>

  </div>

</body>

</html>
