<?php

require '../../../server/classes/class.searchFair.php';

session_start();
$outputHTML = "";
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {

  if (isset($_POST['startDate']) && isset($_POST['endDate'])) {
    $search = new SearchFair();


    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $listOfFairs = $search->searchFairByPeriod($startDate, $endDate);

    $outputHTML = '<tr><th>Fairs</th><th>Date</th></tr>';
    if ($listOfFairs != null) {
      foreach ($listOfFairs as $fair) {
        $out = '<tr><td>';
        $out .= '<a href="../fairOverView.php?fair_id=' . _e($fair['fairId']) . '">' . _e($fair['title']) . '</a>';
        $out .= '</td>';
        $out .= '<td>';
        $out .= '<p>' . _e($fair['start_date']) . ' TO ' . _e($fair['end_date']) . '</p>';
        $out .= '</td></tr>';
        $outputHTML .= $out;
      }
    } else {
      $outputHTML = "No record found";
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
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/searchFair.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seach By Period</title>
  <!-- favicon -->
  <?php include "../../favicon/favicon.php"; ?>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "searchFairByName";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <!-- The flexible grid (content) -->
  <div class=content>
    <div class="mainCol1 g">
      <center>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>
          <h1 class="topTitle">Search By Period <?php if (isset($_GET['fairId'])) echo "to " . $_GET['fairId']  ?></h1>

          <div class="side">
            <label for="startDate">Start Date</label>
            <input type="date" name="startDate" placeholder="Start Date" value="<?php if (isset($_POST['startDate'])) echo $_POST['startDate']; ?>" required>

            <label for="endDate">End Date</label>
            <input type="date" name="endDate" placeholder="End Date" value="<?php if (isset($_POST['endDate'])) echo $_POST['endDate']; ?>" required>
            <button type="submit" name="submit" class="btn">Show</button>

          </div>
          <div>
            <table id="livesearch">
              <?php echo $outputHTML ?>
            </table>
          </div>
        </form>
      </center>
    </div>
  </div>

</body>

</html>
