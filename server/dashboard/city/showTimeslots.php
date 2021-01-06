<?php
/* Code for handeling AJAX request for city fair zone timeslots  */
require '../../../server/classes/class.fair.php';
session_start();

if (isset($_SESSION['loggedin']) && isset($_SESSION['type'])) {
  $fair = new Fair();

  if (isset($_GET['zoneId']) && isset($_GET['date'])) {
    $date = $_GET['date'];
    $zoneId = $_GET['zoneId'];
    $fair->showZoneTimeSlotsByDate($zoneId, $date);
    return;
  }

  if (isset($_GET['zoneId'])) {
    $zoneId = $_GET['zoneId'];

    $table = $fair->showZoneTimeSlots($zoneId);
    return $table;
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
