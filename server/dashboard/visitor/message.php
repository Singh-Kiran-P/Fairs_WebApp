<?php
/* Code for handeling AJAX request for messaging  */
require '../../../server/classes/class.messaging.php';
session_start();
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {
  if (isset($_SESSION['userId'])) {
    $msg = new Messaging();
    //echo msg to font-end if there are msg that are not openend
    $msg->getUnOpenendMsg($_SESSION['userId']);

    // set openend message in database to True
    $msg->msgOpenend($_SESSION['userId']);
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
