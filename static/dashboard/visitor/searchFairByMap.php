<?php

require '../../../server/classes/class.searchFair.php';

session_start();
$outputHTML = "";
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {

  if (isset($_GET['location']) && isset($_GET['filter'])) {
    $search = new SearchFair();
    $baseLocation = $_GET['location'];
    $filter = $_GET['filter'];
    $listOfFairs = [];
    $errorMsg = "";
    if ($baseLocation != "") {
      if ($filter == "closest")
        $listOfFairs = $search->searchSortFairsByLocation($baseLocation, true);
      if ($filter == "farthest")
        $listOfFairs = $search->searchSortFairsByLocation($baseLocation, false);

      $outputHTML = '<tr><th>Fairs</th><th>Location</th><th>Distance</th></tr>';
      if ($listOfFairs != null) {
        foreach ($listOfFairs as $fair) {
          $out = '<tr><td>';
          $out .= '<a href="../fairOverView.php?fair_id=' . _e($fair['fairId']) . '">' . _e($fair['title']) . '</a>';
          $out .= '</td>';
          $out .= '<td>';
          $out .=  _e($fair['location']);
          $out .= '</td>';
          $out .= '<td>';
          $out .=  _e($fair['distance_txt']);
          $out .= '</td></tr>';
          $outputHTML .= $out;
        }
      } else {
        $outputHTML = "No record found";
      }
    } else { //loaction  = ""
      $errorMsg = "Location cannot be empty";
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
  <!-- Mapbox -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
  <link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />

  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/searchFairByMap.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search By Map</title>
</head>

<body onload="initMap()">
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "searchFairByMap";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <!-- The flexible grid (content) -->
  <div class=content>
    <center>
      <!-- MapBox Map -->
      <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.min.js"></script>
      <link rel="stylesheet" href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v4.5.1/mapbox-gl-geocoder.css" type="text/css" />
      <!-- Promise polyfill script required to use Mapbox GL Geocoder in IE 11 -->
      <script src='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.js'></script>
      <link href='https://api.mapbox.com/mapbox-gl-js/v1.12.0/mapbox-gl.css' rel='stylesheet' />

      <div id="map-container">
        <div id="map">
        </div>
      </div>
      <div class="mainCol">
        <H1>Use Fair location filter</H1>
        <!-- search location filter -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
          <input type="text" name="location" id="" placeholder="Location ez. 'Hasselt' or 3500" value="" />
          <select name="filter" id="">
            <option value="closest">Filter On:</option>
            <option value="closest">closest</option>
            <option value="farthest">farthest</option>
          </select>
          <button type="submit" name="" id="btn">Search</button>
        </form>


        <table class="search">
          <?php echo $outputHTML ?>
        </table>

        <p id="error">
          <?php

          if (isset($errorMsg)) echo _e($errorMsg);

          ?>
        </p>
      </div>
    </center>
  </div>
  <!-- Script -->
  <script src="searchFairByMap.js"></script>
</body>

<table class="search">

</table>

</html>
