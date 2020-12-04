<?php
require '../../../server/classes/class.messaging.php';
require '../../../server/classes/class.accounts.php';

session_start();
if (!(isset($_SESSION['loggedin']) && isset($_SESSION['type']) && $_SESSION['type'] == "visitor"))
  header('Location: ' . $rootURL . '/~kiransingh/project/static/dashboard/unauthorized.php');

$msgFrom = $_SESSION['userId'];

$account = new Accounts();
$listOfVisitors = $account->getAllVisitors();

$outHTML_otherVisitors = '';
if (count($listOfVisitors) > 0) {

  foreach ($listOfVisitors as $visitor) {
    if ($visitor['userId'] != $msgFrom)
      $outHTML_otherVisitors .= '<option value="' . $visitor['userId'] . '">' . $visitor['name'] . '</option>';
  }
}



$messaging = new Messaging();

// process select user FORM
if ((isset($_GET['msgTo']) && $_GET['msgTo'] != "")) {
  $msgTo = $_GET['msgTo'];
  $listWithMessages = $messaging->getMessages($msgFrom, $msgTo);
  $outHTML_MsgBox = $messaging->buildMsgBox($listWithMessages, $msgFrom, $msgTo);

  if (count($listOfVisitors) > 0) {
    $outHTML_otherVisitors = '';
    foreach ($listOfVisitors as $visitor) {
      if ($visitor['userId'] == $msgTo)
        $outHTML_otherVisitors .= '<option value="' . $visitor['userId'] . '" selected>' . $visitor['name'] . '</option>';
      else
        $outHTML_otherVisitors .= '<option value="' . $visitor['userId'] . '">' . $visitor['name'] . '</option>';
    }
  }
}

if ((isset($_GET['msgTo']) && $_GET['msgTo'] != "") && (isset($_POST['sendMsg']) && isset($_POST['msg'])&& $_POST['msg'] != "")) {

  $msgFrom = $_SESSION['userId'];
  $msgTo = $_GET['msgTo'];
  $msg = $_POST['msg'];


  //send msg
  $messaging->sendMsg($msgFrom, $msgTo, $msg);

  $listWithMessages = $messaging->getMessages($msgFrom, $msgTo);
  $outHTML_MsgBox = $messaging->buildMsgBox($listWithMessages, $msgFrom, $msgTo);
}


?>



<!-- HTML Code -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="/~kiransingh/project/static/style-sheets/message.css">
  <title>Message</title>
</head>

<body>

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "profile";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>

  <div class="content">

    <div class="contacts">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <select name="msgTo">
          <option value="">Chose user ...</option>
          <?php echo $outHTML_otherVisitors; ?>
        </select>
        <input type="submit" name="choseUser" value="chat">
      </form>
    </div>

    <div class="messageBox" <?php if (!isset($outHTML_MsgBox)) {
                              echo " style='display: none'";
                            } ?>>
      <div class="messages">
        <?php echo $outHTML_MsgBox; ?>
      </div>

      <div class="sendMessage">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?msgTo=<?php echo $msgTo ?>" method="post">
          <input type="text" name="msg">

          <input type="submit" name="sendMsg" value="send">
          <a href="?msgTo=<?php echo $msgTo ?>">refresh</a>
        </form>
      </div>
    </div>
  </div>
</body>

<script>
  var elem = document.getElementsByClassName('messages')[0];
  elem.scrollTop = elem.scrollHeight;
</script>

</html>
