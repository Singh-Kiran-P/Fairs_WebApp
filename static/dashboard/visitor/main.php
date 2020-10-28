<?php
require '../../../server/classes/class.city.php';
require '../../../server/classes/class.accounts.php';

session_start();

if (isset($_SESSION['loggedin'])) {
  $userId = $_SESSION['userId'];
  $type = $_SESSION['type'];
  $account = new Accounts();
  $account->init($userId,$type);

  $username = $account->getUsername();
  $email = $account->getEmail();
  $type = $account->getType();
  $name = $account->getName();


} else {
  header("Location: ../unauthorized.php");
}
?>
