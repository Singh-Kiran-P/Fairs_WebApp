<?php

require '../../../server/classes/class.searchFair.php';

session_start();
$outputHTML = "";
if (isset($_SESSION['loggedin'])) {
  if (isset($_GET['title'])) {
    $search = new SearchFair();


    $title = $_GET['title'];
    $listOfFairs = $search->searchByName($title);

    $outputHTML = '<tr><th>Fairs</th></tr>';
    if ($listOfFairs != null) {
      foreach ($listOfFairs as $fair) {
        $out = '<tr><td>';
        $out .= '<a href="fairView.php?fair_id=' . $fair['fairId'] . '">' . $fair['title'] . '</a>';
        $out .= '</td></tr>';
        $outputHTML .= $out;
      }
    } else {
      $outputHTML = "No record found";
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
    </center>
  </div>
  <!-- Script -->
  <script src="searchFairByMap.js"></script>
</body>

</html>
