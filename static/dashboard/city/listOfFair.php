<?php
include_once '../../../server/classes/class.fair.php';
include_once '../../../server/classes/class.model.fair.php';
session_start();

if (isset($_SESSION['loggedin'])) {
    $cityId = $_SESSION['cityId'];
    $fair = new Fair();

    $listOfFairs = $fair->getListOfFairs($cityId);

    foreach ($listOfFairs as $fairRow) {
      print_r($fairRow->getVar());
      echo "<br>";
    }


} else {
  header("Location: ../unauthorized.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>List Of Fairs</title>
</head>

<body>
  <H3>
    <ul>
      <li>hasselr 2020</li>
      <li>hasselr 2020</li>
      <li>hasselr 2020</li>
      <li>hasselr 2020</li>
      <li>hasselr 2020</li>
    </ul>
  </H3>
</body>

</html>
