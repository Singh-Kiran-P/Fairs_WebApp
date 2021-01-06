<?php
include_once "../../classes/class.accounts.php";

$error = 'Invalid link';
$success = '';
if (isset($_GET['activationHash'])) {
  $acc = new Accounts();
  $userId = $acc->_checkIfActivationHashIsValid($_GET['activationHash']);
  if ($userId != null) {
    $acc->activateAccount($userId);
    $success = "Account successfully activated!";
    $error = "";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <title>Activate account</title>
</head>

<body>
  <article>
    <h1> <?php echo $success; ?></h1>
    <h1>
      <?php echo $error; ?>
    </h1>
    <a href="../../../static/Auth/login.php">Go to Home page</a>

  </article>

</body>

</html>
