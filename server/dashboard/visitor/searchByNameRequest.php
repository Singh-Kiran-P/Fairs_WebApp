<?php
require '../../../server/classes/class.searchFair.php';
session_start();
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {
  $search = new SearchFair();

  $title = $_GET['title'];
  $listOfFairs = $search->searchByName($title);

  $outputHTML = '<tr><th>Fairs</th></tr>';
  if ($listOfFairs != null) {
    foreach ($listOfFairs as $fair) {
      $out = '<tr><td>';
      $out .= '<a href="../fairOverView.php?fair_id=' . $fair['fairId'] . '">' . $fair['title'] . '</a>';
      $out .= '</td></tr>';
      $outputHTML .= $out;
    }
  } else {
    $outputHTML = "No record found";
  }

  echo $outputHTML;
} else {
  header("Location: " . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
