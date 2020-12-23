<?php

require '../../../server/classes/class.searchFair.php';

session_start();
$outputHTML = "";
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {

  if (isset($_GET['title'])) {

    $search = new SearchFair();

    $listOfFairs = $search->searchByName($_GET['title'], true);

    if ($listOfFairs != null) {
      $outputHTML = 'Up coming / On Going<tr><th>Fairs</th><th>Date</th</tr>';
      foreach ($listOfFairs as $fair) {
        $out = '<tr><td>';
        $out .= '<a href="../fairOverView.php?fair_id=' . $fair['fairId'] . '">' . $fair['title'] . '</a></td>';
        $out .= '<td><p>' . $fair['start_date'] . ' TO ' . $fair['end_date'] . '</p>';
        $out .= '</td></tr></table>';
        $outputHTML .= $out;
      }
    } else {
      $outputHTML = "No record found";
    }

    $listOfFairs = $search->searchByName($_GET['title'], false);

    if ($listOfFairs != null) {
      $outputHTML .= '<table>Old Fairs<tr><th>Fairs</th><th>Date</th</tr>';
      foreach ($listOfFairs as $fair) {
        $out = '<tr><td>';
        $out .= '<a href="../fairOverView.php?fair_id=' . $fair['fairId'] . '">' . $fair['title'] . '</a></td>';
        $out .= '<td><p>' . $fair['start_date'] . ' TO ' . $fair['end_date'] . '</p>';
        $out .= '</td></tr>';
        $outputHTML .= $out;
      }
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
  <title>Seach By Name</title>
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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method=" post" class="content" id="form" onsubmit="return validateForm()" enctype='multipart/form-data'>
          <h1 class="topTitle">Search By Name <?php if (isset($_GET['fairId'])) echo "to " . $_GET['fairId']  ?></h1>

          <div class="side">
            <input type="text" name="title" placeholder="Search.." value="<?php if (isset($_GET['title'])) echo $_GET['title']; ?>" required onkeyup="showResult(this.value)" autocomplete="off">
          </div>
          <div id="result">
            <table id="livesearch">
              <?php echo $outputHTML ?>
            </table>
          </div>
        </form>
      </center>
    </div>
  </div>

</body>
<!-- Script -->
<script src="searchFairByName.js"></script>

</html>
