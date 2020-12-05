<?php
require '../../../server/classes/class.city.php';
require '../../../server/classes/class.reservation.php';
require '../../../server/classes/class.accounts.php';

session_start();

if (isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor") {

  $userId = $_SESSION['userId'];
  $type = $_SESSION['type'];
  $account = new Accounts();
  $account->init($userId, $type);

  $username = $account->getUsername();
  $email = $account->getEmail();
  $type = $account->getType();
  $name = $account->getName();

  $desc = $account->getShort_desc();
  $telephone = $account->getTelephone();

  /* Make the reservation table */
  $outHTML_reservations = '';
  $outHTML_notification = "";
  $reservation = new Reservation();
  $reservationList = $reservation->getReservations($_SESSION["userId"]);

  if ($reservationList !== null) {
    $outHTML_reservations .= '<h2 class="title">Reservations</h2>';
    $outHTML_reservations .= '<table>';
    $outHTML_reservations .= '  <tr>';
    $outHTML_reservations .= '    <th>Fair Title</th>';
    $outHTML_reservations .= '    <th>Zone Title</th>';
    $outHTML_reservations .= '    <th>For</th>';
    $outHTML_reservations .= '    <th>Date/Time</th>';
    $outHTML_reservations .= '    <th>Actions</th>';
    $outHTML_reservations .= '  </tr>';

    foreach ($reservationList as $item) {

      // create notification
      $today_dt = new DateTime(date("Y-m-d"));
      $expire_dt = new DateTime($item['date']);
      if ($expire_dt < $today_dt)
        $outHTML_notification .= "If you liked " . $item['fairTitle'] . "'s zone " . $item['zoneTitle'] . ",you write a review by clicking on 👍<br>";

      $outHTML_reservations .= '  <tr>';
      $outHTML_reservations .= '      <td>' . $item['fairTitle'] . '</td>';
      $outHTML_reservations .= '      <td>' . $item['zoneTitle'] . '</td>';
      $outHTML_reservations .= '      <td>' . $item['nOfPeople'] . '</td>';
      $outHTML_reservations .= '      <td>On ' . $item['date'] . ' from ' . $item['opening_slot'] . ' To ' . $item['closing_slot'] . '</td>';
      $outHTML_reservations .= '      <td class="actions">';
      $outHTML_reservations .= '        <!-- Go to fair info -->';
      $outHTML_reservations .= '        <a class="reservation_btn" href="../fairOverView.php?fair_id=' . $item['fair_id'] . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-fighter-jet "></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '        <!-- Go to review page -->';
      $outHTML_reservations .= '        <a class="reservation_btn" href="review.php?reservationId=' . $item['reservation_id'] . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-thumbs-up"></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '        <!-- Cancel reservation -->';
      $outHTML_reservations .= '        <a class="reservation_btn" href="../../../server/dashboard/visitor/cancelReservation.php?reservationId=' . $item['reservation_id'] . '&zoneslot_id=' . $item['zoneslot_id'] . '&people=' . $item['nOfPeople'] . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-close"></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '      </td>';
      $outHTML_reservations .= '   </tr>';
    }
    $outHTML_reservations .= '   </table>';
  }

  /* Make the waitingList table */
  $outHTML_waitingList = '';

  $waitingList = $reservation->getWaitingList($_SESSION["userId"]);

  if ($waitingList != null) {
    $outHTML_waitingList .= '<h2 class="title">Waiting List</h2>';
    $outHTML_waitingList .= '<table>';
    $outHTML_waitingList .= '  <tr>';
    $outHTML_waitingList .= '    <th>Fair Title</th>';
    $outHTML_waitingList .= '    <th>Zone Title</th>';
    $outHTML_waitingList .= '    <th>Free spots</th>';
    $outHTML_waitingList .= '    <th>Date/Time</th>';
    $outHTML_waitingList .= '    <th>Actions</th>';
    $outHTML_waitingList .= '  </tr>';

    foreach ($waitingList as $item) {
      $outHTML_waitingList .= '  <tr>';

      if ($item['free_slots'] > 0) {
        $outHTML_waitingList .= '      <td class="open_spot">' . $item['fairTitle'] . '</td>';
        $outHTML_waitingList .= '      <td class="open_spot">' . $item['zoneTitle'] . '</td>';
        $outHTML_waitingList .= '      <td class="open_spot">' . $item['free_slots'] . '</td>';
      } else {
        $outHTML_waitingList .= '      <td >' . $item['fairTitle'] . '</td>';
        $outHTML_waitingList .= '      <td>' . $item['zoneTitle'] . '</td>';
        $outHTML_waitingList .= '      <td>' . $item['free_slots'] . '</td>';
      }
      $outHTML_waitingList .= '      <td>On ' . $item['date'] . ' from ' . $item['opening_slot'] . ' To ' . $item['closing_slot'] . '</td>';
      $outHTML_waitingList .= '      <td class="actions">';
      $outHTML_waitingList .= '        <!-- Go to fair info -->';
      $outHTML_waitingList .= '        <a class="reservation_btn" href="../ZoneOverView.php?zoneId=' . $item['zone_id'] . '&fairId=' . $item['fair_id'] . '">';
      $outHTML_waitingList .= '          <span> <i class="fa fa-fighter-jet "></i></span>';
      $outHTML_waitingList .= '        </a>';
      $outHTML_waitingList .= '        <a class="reservation_btn" href="../../../server/dashboard/visitor/removeFromWaitingList.php?userId=' . $_SESSION['userId'] . '&zoneslot_id=' . $item['zoneslot_id'] . '">';
      $outHTML_waitingList .= '          <span> <i class="fa fa-close "></i></span>';
      $outHTML_waitingList .= '        </a>';
      $outHTML_waitingList .= '      </td>';
      $outHTML_waitingList .= '   </tr>';
    }
    $outHTML_waitingList .= '   </table>';
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>

<!DOCTYPE html>
<html>

<head>
  <title>Home</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/profile.css">
  <!-- Add icon library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- Alertify JS -->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <!-- CSS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
  <!-- Semantic UI theme -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />


  <script src="profile.js"></script>

</head>

<body onload="onLoad();">

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "profile";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <!-- Profile foto -->
  <div class="profileImg">
    <?php
    echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile_img/$userId.jpg' alt=''>"
    ?>
  </div>

  <!-- The flexible grid (content) -->
  <form action="" method="post" class="content" id="form">
    <!-- Messaging link -->
    <a class="messaging_btn" href="message.php"><span> <i class="fa fa-comments" aria-hidden="true"></i></span></a>

    <div class="mainCol1 g">
      <center>
        Name:
        <input type="text" placeholder="Name" value="<?php echo $name; ?>" disabled>
        Email:
        <input type="text" placeholder="Email" value="<?php echo $email; ?>">


      </center>
    </div>
    <div class="mainCol2 b">
      <center>
        Username:
        <input type="text" placeholder="Username" value="<?php echo $username; ?>">
        Type:
        <input type="text" placeholder="Type" value="<?php echo $type; ?>" disabled>
      </center>
    </div>


  </form>


  <!-- Reservations -->
  <center class="reservations">
    <?php
    if ($outHTML_notification != "") {
      echo "<div class='notification'>Notifications:<br>";
      echo $outHTML_notification;
      echo '</div>';
    }
    echo $outHTML_reservations; ?>
  </center>

  <!-- Waiting List -->
  <center class="reservations">
    <?php echo $outHTML_waitingList; ?>
  </center>
  </div>
</body>

</html>
