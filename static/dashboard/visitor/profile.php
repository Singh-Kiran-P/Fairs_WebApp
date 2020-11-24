<?php
require '../../../server/classes/class.city.php';
require '../../../server/classes/class.reservation.php';
require '../../../server/classes/class.accounts.php';

session_start();

if (isset($_SESSION['loggedin'])) {
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
      $outHTML_reservations .= '        <a class="reservation_btn" href="../../../server/dashboard/visitor/cancelReservation.php?reservationId=' . $item['reservation_id'] . '">';
      $outHTML_reservations .= '          <span> <i class="fa fa-close"></i></span>';
      $outHTML_reservations .= '        </a>';
      $outHTML_reservations .= '      </td>';
      $outHTML_reservations .= '   </tr>';
    }
  }
} else {
  header("Location: ../unauthorized.php");
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
</head>

<body>

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
    <?php echo $outHTML_reservations; ?>
  </center>
  </div>
</body>

</html>
