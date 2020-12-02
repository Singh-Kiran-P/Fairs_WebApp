<?php
/* Code for handeling AJAX request for city fair zone */

require '../../../server/classes/class.fair.php';
session_start();

if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "city") {
  $fair = new Fair();

  if (isset($_GET['zoneId'])) {
    $zoneId = $_GET['zoneId'];

    $json = $fair->getZone($zoneId);
    echo json_encode($json);
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
