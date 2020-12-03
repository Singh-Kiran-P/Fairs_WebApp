<?php
session_start();
require '../../../server/classes/class.searchFair.php';
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {
  $s = new SearchFair();
  $msg = $s->getGeoJsonOfAllFairs();
  echo json_encode($msg);
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
