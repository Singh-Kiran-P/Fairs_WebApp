<?php
/* Code for handeling AJAX request for city fair zone timeslots  */
require '../../../server/classes/class.reservation.php';
session_start();

if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {

  $reservation = new Reservation();

  if (isset($_GET['reservationId']) && isset($_GET['people']) && isset($_GET['zoneslot_id'])) {

    $reservationId = $_GET['reservationId'];
    $people = $_GET['people'];
    $zoneslot_id = $_GET['zoneslot_id'];
    $reservation->cancelReservation($reservationId);
    $reservation->updateZoneslot($zoneslot_id, $people, true);
    header("Location: ../../../static/dashboard/visitor/profile.php ");
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
