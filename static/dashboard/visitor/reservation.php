<?php
require '../../../server/classes/class.reservation.php';

session_start();
if (isset($_POST['zoneId']) && isset($_POST['fairId'])) {
  $_SESSION['fairId'] = $_POST['fairId'];
  $_SESSION['zoneId'] = $_POST['zoneId'];
}

$outputHTML = "";
if (isset($_SESSION['loggedin'])) {
  if (isset($_SESSION['zoneId']) && isset($_SESSION['fairId'])) {
    $reservation = new Reservation();
    $fair = new Fair();

    $fairInfo = $fair->getFairModel($_SESSION['fairId'])->getVar();
    $zoneInfo = $fair->getZone($_SESSION['zoneId']);

    if (isset($_POST['date'])  && isset($_POST['nOfPeople'])) {
      $reservationDateTime = new DateTime($_POST['date']);
      $reservationDate = $reservationDateTime->format('m/d/Y');
      $reservationTimeSlot = $reservationDateTime->format('H:i:s');
      $reservationPeople = $_POST['nOfPeople'];

      $msg = $reservation->checkSpot($_SESSION['zoneId'], $reservationDate, $reservationTimeSlot, $reservationPeople);

      if ($msg['msg'] == "" && isset($_POST['submit'])) { // enough place
        $out = $reservation->updateZoneslot($msg['zoneslot_id'], $reservationPeople, false);
        if ($out['msg'] == "") {
          //booking
          $out = $reservation->book($_SESSION['userId'], $_SESSION['fairId'], $msg['zoneslot_id'], $reservationPeople);
          if ($out['msg'] == "")
            header('Location: ' . "profile.php");
        }
      } else { // not enough place, add user to a waiting list
        $zoneslot_id = $msg['zoneslot_id'];
        $msg['msg'] .= $reservation->addToWaitingList($zoneslot_id, $_SESSION['userId'])['msg'];
      }
    }
  }
} else {
  header("Location: ../unauthorized.php");
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Reservation</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/~kiransingh/project/static/style-sheets/form.css">
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "searchFairByName";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <div class="content">
    <!-- The flexible grid (content) -->
    <div class="flexbox">

      <!-- Login form -->
      <div id="form">
        <center>
          <h1> Reservation</h1>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <!-- fair title -->
            <label for="">
              <div class="l">
                <H3>Fair Title:</H3>
              </div>
              <div class="l">
                <H4> <?php echo $fairInfo["title"]; ?></H4>
              </div>

            </label>

            <!-- zone title -->
            <label for="">
              <div class="l">
                <H3>Zone Title:</H3>
              </div>
              <div class="l">
                <H4><?php echo $zoneInfo["title"]; ?></H4>
              </div>
            </label>

            <!-- Date for reservation -->
            <label for="">
              <div class="l">
                <H3>Date for reservation:</H3>
              </div>
              <div class="l">
                <H4>
                  <input type="datetime-local" name="date" class="date_time" id="" min="<?php echo $fairInfo['start_date'] ?>" max="<?php echo $fairInfo['end_date'] ?>" required>
                </H4>
              </div>
            </label>



            <!-- number of people for reservation -->
            <label for="">
              <div class="l">
                <H3>Number of people:</H3>
              </div>
              <div class="l">
                <H4>
                  <input type="number" name="nOfPeople" class="date_time" id="" min="1" max="8" value="1" required>
                </H4>
              </div>
            </label>

            <button type="submit" name="submit" id="btn">Book</button>

          </form>

          <p id="error">
            <?php
            if (isset($msg))
              echo $msg['msg'];
            ?>
          </p>
        </center>
        <table style="width:100%">
          <tr>
            <th>Date</th>
            <th>Opening Slot</th>
            <th>Closing Slot</th>
            <th>Free</th>
          </tr>
          <?php
          echo $fair->showZoneTimeSlots($_SESSION['zoneId']);
          ?>
        </table>
      </div>
    </div>
  </div>
</body>

<!-- Script -->
<script src="login.js"></script>

</html>
