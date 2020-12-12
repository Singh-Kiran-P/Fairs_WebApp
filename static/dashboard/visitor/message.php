<!-- https://onaircode.com/html-css-chat-box-examples/ -->
<!-- https://codepen.io/kristinak/pen/bXqayB -->
<!-- https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_js_dropdown -->

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
      $outHTML_otherVisitors .= '<a href="message.php?msgTo=' . $visitor['userId'] . '">' . $visitor['name'] . '</a>';
  }
}


$HTMLcurrentUser = "Choose User:";
$messaging = new Messaging();

// process select user FORM
if ((isset($_GET['msgTo']) && $_GET['msgTo'] != "")) {
  $msgTo = $_GET['msgTo'];
  $listWithMessages = $messaging->getMessages($msgFrom, $msgTo);
  $outHTML_MsgBox = $messaging->buildMsgBox($listWithMessages, $msgFrom, $msgTo);

  if (count($listOfVisitors) > 0) {
    $outHTML_otherVisitors = '';
    foreach ($listOfVisitors as $visitor) {
      if ($visitor['userId'] != $msgFrom) {
        if ($visitor['userId'] == $msgTo) {
          $outHTML_otherVisitors .= '<a href="message.php?msgTo=' . $visitor['userId'] . '" class="active">' . $visitor['name'] . '</a>';
          $HTMLcurrentUser =  $visitor['name'];
        } else
          $outHTML_otherVisitors .= '<a href="message.php?msgTo=' . $visitor['userId'] . '">' . $visitor['name'] . '</a>';
      }
    }
  }
}

if ((isset($_GET['msgTo']) && $_GET['msgTo'] != "") && (isset($_POST['sendMsg']) && isset($_POST['msg']) && $_POST['msg'] != "")) {

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
  <!-- Add icon library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


  <!-- Alertify JS -->
  <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
  <!-- CSS -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
  <!-- Semantic UI theme -->
  <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css" />
  <title>Messaging</title>
</head>

<body onload="onLoad();">

  <header>
    <!-- navbar -->
    <?php
    $typeNav = "profile";
    include '../../componets/navbarTopVisitor.php';
    ?>
  </header>



  <div class="content">
    <div class="dropdown">
      <button onclick="myFunction()" class="dropbtn"><?php echo $HTMLcurrentUser; ?></button>
      <div id="myDropdown" class="dropdown-content">
        <?php echo $outHTML_otherVisitors; ?>

      </div>
    </div>

    <!-- <div class="contacts"> -->
    <!-- <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
        <select name="msgTo">
          <option value="">Chose user ...</option>

        </select>
        <input type="submit" name="choseUser" value="chat">
      </form> -->


    <!-- </div> -->


    <div class="messageBox" <?php if (!isset($outHTML_MsgBox)) {
                              echo " style='display: none'";
                            } ?>>
      <div class="messages">
        <?php echo $outHTML_MsgBox; ?>
      </div>

      <div class="sendMessage">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?msgTo=<?php echo $msgTo ?>" method="post">
          <input type="text" name="msg" id="enter" placeholder="Type a message...">
          <div class="buttons">
            <button type="submit" name="sendMsg"><i class="fa fa-paper-plane" aria-hidden="true"></i>
            </button>
            <a href="?msgTo=<?php echo $msgTo ?>"><i class="fa fa-refresh" aria-hidden="true"></i></a>
          </div>
        </form>
      </div>
    </div>
  </div>



</body>

<script src="message.js"></script>

<script>
  var elem = document.getElementsByClassName('messages')[0];
  elem.scrollTop = elem.scrollHeight;
</script>

</html>
