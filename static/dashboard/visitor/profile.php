<?php
require '../../../server/classes/class.reservation.php';
require '../../../server/classes/class.accounts.php';
require '../../../server/classes/class.messaging.php';

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
    $outHTML_reservations .= '<div class="table">';
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

      if ($expire_dt < $today_dt) {

        $outHTML_notification .= "<li>If you liked " . _e($item['fairTitle']) . "'s zone " . _e($item['zoneTitle']) . ",you can write a review by clicking on 👍</li>";
      }

      $outHTML_reservations .= '  <tr>';
      $outHTML_reservations .= '      <td>' . _e($item['fairTitle']) . '</td>';
      $outHTML_reservations .= '      <td>' . _e($item['zoneTitle']) . '</td>';
      $outHTML_reservations .= '      <td>' . _e($item['nOfPeople']) . '</td>';
      $outHTML_reservations .= '      <td>On ' . _e($item['date']) . ' from ' . _e($item['opening_slot']) . ' To ' . _e($item['closing_slot']) . '</td>';
      $outHTML_reservations .= '      <td class="actions">';
      $outHTML_reservations .= '        <!-- Go to fair info -->';
      $outHTML_reservations .= '        <a class="reservation_btn" href="../fairOverView.php?fair_id=' . _e($item['fair_id']) . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-fighter-jet "></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '        <!-- Go to review page -->';
      $outHTML_reservations .= '        <a class="reservation_btn" href="review.php?reservationId=' . _e($item['reservation_id']) . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-thumbs-up"></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '        <!-- Cancel reservation -->';
      $outHTML_reservations .= '        <a class="reservation_btn" href="../../../server/dashboard/visitor/cancelReservation.php?reservationId=' . $item['reservation_id'] . '&zoneslot_id=' . _e($item['zoneslot_id']) . '&people=' . _e($item['nOfPeople']) . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-close"></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '      </td>';
      $outHTML_reservations .= '   </tr>';
    }
    $outHTML_reservations .= '   </table>';
    $outHTML_reservations .= '   </div>';
  }

  /* Make the waitingList table */
  $outHTML_waitingList = '';

  $waitingList = $reservation->getWaitingList($_SESSION["userId"]);

  if ($waitingList != null) {
    $outHTML_waitingList .= '<h2 class="title">Waiting List</h2>';
    $outHTML_waitingList .= '<div class="table">';

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
        $outHTML_waitingList .= '      <td class="open_spot">' . _e($item['fairTitle']) . '</td>';
        $outHTML_waitingList .= '      <td class="open_spot">' . _e($item['zoneTitle']) . '</td>';
        $outHTML_waitingList .= '      <td class="open_spot">' . _e($item['free_slots']) . '</td>';
      } else {
        $outHTML_waitingList .= '      <td >' . _e($item['fairTitle']) . '</td>';
        $outHTML_waitingList .= '      <td>' . _e($item['zoneTitle']) . '</td>';
        $outHTML_waitingList .= '      <td>' . _e($item['free_slots']) . '</td>';
      }
      $outHTML_waitingList .= '      <td>On ' . _e($item['date']) . ' from ' . _e($item['opening_slot']) . ' To ' . _e($item['closing_slot']) . '</td>';
      $outHTML_waitingList .= '      <td class="actions">';
      $outHTML_waitingList .= '        <!-- Go to fair info -->';
      $outHTML_waitingList .= '        <a class="reservation_btn" href="../ZoneOverView.php?zoneId=' . _e($item['zone_id']) . '&fairId=' . _e($item['fair_id']) . '">';
      $outHTML_waitingList .= '          <span> <i class="fa fa-fighter-jet "></i></span>';
      $outHTML_waitingList .= '        </a>';
      $outHTML_waitingList .= '        <a class="reservation_btn" href="../../../server/dashboard/visitor/removeFromWaitingList.php?userId=' . $_SESSION['userId'] . '&zoneslot_id=' . _e($item['zoneslot_id']) . '">';
      $outHTML_waitingList .= '          <span> <i class="fa fa-close "></i></span>';
      $outHTML_waitingList .= '        </a>';
      $outHTML_waitingList .= '      </td>';
      $outHTML_waitingList .= '   </tr>';
    }
    $outHTML_waitingList .= '   </table>';
    $outHTML_waitingList .= '   </div>';
  }

  // get Message List
  $messaging = new Messaging();

  $msg = $messaging->getUnOpenendMsg($_SESSION['userId'], "true");
  $OUTHTML_MSG = "";
  foreach ($msg as $m) {
    $OUTHTML_MSG .= '<div class="msg">';
    $OUTHTML_MSG .= $m['msgCount'] . ' new messages from <a href="message.php?msgTo=' . _e($m['user_id']) . '&opened=true"><b id="linkinnotification" >' . _e($m['msgFrom']) . '</b></a>';
  }

  // Notification if admin updated something
  $notifications = $account->getNotifications($userId);
  foreach ($notifications as $notification) {
    $OUTHTML_MSG .= '<li>' . _e($notification['msg']) . '<a class="fl-right" href="../../../server/dashboard/visitor/deleteNotification.php?id=' . _e($notification['notification_id']) . '">X</a> </li>';
  }
} else {
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Profile</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/profile.css">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/alert.css">
  <!-- Add icon library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="profile.js"></script>
  <!-- favicon -->
  <?php include "../../favicon/favicon.php"; ?>

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
    //echo "<img class='topImg' src='/~kiransingh/project/server/uploads/profile_img/$userId.jpg' alt='Profile Img'>"
    ?>
  </div>
  <div class="content">
    <!-- The flexible grid (content) -->
    <form action="" method="post" id="form">
      <!-- Messaging link -->
      <a class="messaging_btn" href="message.php"><span> <i class="fa fa-comments" aria-hidden="true"></i></span></a>

      <div class="mainCol1 g">
        <div class="info">
        <p>Name: <?php echo $name; ?> </p>
        <p>Email: <?php echo $email; ?> </p>


        <p>Username: <?php echo $username; ?> </p>
        <p>Type: <?php echo $type; ?> </p>
        </div>
        <div id="alert-area">
          <p <?php if ($OUTHTML_MSG == "") echo "class='hidden'"; ?>>Meldingen: <a class="fl-right m-r" href="../../../server/dashboard/visitor/deleteNotification.php?id=">All X</a></p>
          <?php echo $OUTHTML_MSG; ?>
        </div>
      </div>


    </form>

    <!-- Reservations -->
    <center class="notifications">
      <?php
      if ($outHTML_notification != "") {
        echo "<div class='notification_inside'>Notifications:<br>";
        echo "<ul>";
        echo $outHTML_notification;
        echo '</ul>';
        echo "</div>";
      }
      echo $outHTML_reservations; ?>
    </center>

    <!-- Waiting List -->
    <center class="notifications">
      <?php echo $outHTML_waitingList; ?>
    </center>
  </div>

</body>

</html>
