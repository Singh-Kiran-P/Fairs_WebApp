<?php
/* Code for handeling AJAX request for city fair zone timeslots  */
require '../../../server/classes/class.reservation.php';
session_start();

if (isset($_SESSION['loggedin'])) {
  $reservation = new Reservation();

  if (isset($_GET['userId']) && isset($_GET['zoneslot_id'])) {

    $zonneSlotId = $_GET['zoneslot_id'];
    $userId = $_GET['userId'];
    $reservation->removeFromWaitingList($userId, $zonneSlotId);
    header("Location: ../../../static/dashboard/visitor/profile.php ");
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
