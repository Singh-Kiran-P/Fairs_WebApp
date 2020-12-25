<?php
/* Code for handeling AJAX request for city fair zone timeslots  */
require '../../../server/classes/class.accounts.php';
session_start();
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {


  $acc  = new accounts();

  if (isset($_GET['id'])) {

    $n_id = $_GET['id'];
    $userId = $_SESSION['userId'];
    if ($n_id != '')
      $acc->deleteNotification($n_id, $userId, false);
    else
      $acc->deleteNotification($n_id, $userId, true);

    header("Location: ../../../static/dashboard/visitor/profile.php ");
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
