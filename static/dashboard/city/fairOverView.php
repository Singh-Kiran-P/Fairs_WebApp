<?php
require '../../../server/classes/class.fair.php';
session_start();

if (isset($_SESSION['loggedin'])) {
} else {
  header("Location: ../unauthorized.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/city_forms.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Fair</title>
</head>

<body>
  <header>
    <!-- navbar -->
    <?php
    $typeNav = "city_nav";
    include '../../componets/navbarTop.php';
    ?>
  </header>

  <!-- The flexible grid (content) -->
  <div class="content w60">

    <div class="mainCol1">
      <center>
        <h1 class="topTitle">Zone timeslots of <?php echo $_GET['fair_Title']; ?> </h1>

        <div class="sidebyside">
          <select name="users" onchange="showUser(this.value)">
            <option value="">Select a Zone:</option>
            <option value="1">Peter Griffin</option>
            <option value="2">Lois Griffin</option>
            <option value="3">Joseph Swanson</option>
            <option value="4">Glenn Quagmire</option>
          </select>
          <select name="users" onchange="showUser(this.value)">
            <option value="">Select a Date:</option>
            <option value="1">Peter Griffin</option>
            <option value="2">Lois Griffin</option>
            <option value="3">Joseph Swanson</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
            <option value="4">Glenn Quagmire</option>
          </select>
        </div>
        <table class="zoneTimeslotstable">
          <tr>
            <th>Date</th>
            <th>Start time</th>
            <th>End time</th>
            <th>Open</th>
          </tr>
          <tr>
            <td>Jill</td>
            <td>Smith</td>
            <td>50</td>
            <td>50</td>
          </tr>
          <tr>
            <td>Eve</td>
            <td>Jackson</td>
            <td>94</td>
            <td>94</td>
          </tr>
        </table>
        <p id="error">
          <?php
          if (isset($_POST['submit'])) {
            echo $errorMsg;
          }
          ?>
        </p>

      </center>
    </div>
  </div>

</body>
<!-- Script -->
<script src="addFair.js"></script>

</html>
