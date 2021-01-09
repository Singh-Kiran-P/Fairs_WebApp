<?php
require '../../../server/classes/class.searchFair.php';
session_start();
if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {
  $search = new SearchFair();

  $title = $_GET['title'];
  $listOfFairs = $search->searchByName($title, true);

  if ($listOfFairs != null) {
    $outputHTML = 'Up coming / On Going<tr><th>Fairs</th><th>Date</th</tr>';
    foreach ($listOfFairs as $fair) {
      $out = '<tr><td>';
      $out .= '<a href="../fairOverView.php?fair_id=' . $fair['fairId'] . '">' . $fair['title'] . '</a></td>';
      $out .= '<td><p>' . $fair['start_date'] . ' TO ' . $fair['end_date'] . '</p>';
      $out .= '</td></tr></table>';
      $outputHTML .= $out;
    }
  } else {
    $outputHTML = "No record found<br>";
  }

  $listOfFairs = $search->searchByName($title, false);

  if ($listOfFairs != null) {
    $outputHTML .= '<table>Old Fairs<tr><th>Fairs</th><th>Date</th</tr>';
    foreach ($listOfFairs as $fair) {
      $out = '<tr><td>';
      $out .= '<a href="../fairOverView.php?fair_id=' . $fair['fairId'] . '">' . $fair['title'] . '</a></td>';
      $out .= '<td><p>' . $fair['start_date'] . ' TO ' . $fair['end_date'] . '</p>';
      $out .= '</td></tr>';
      $outputHTML .= $out;
    }
  }
  echo $outputHTML;
} else {
  header("Location: " . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}
