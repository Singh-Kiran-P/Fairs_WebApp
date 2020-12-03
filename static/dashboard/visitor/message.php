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
      <form action="" method="post">
        <select name="" id="">
          <option value="">user1</option>
          <option value="">user2</option>
          <option value="">user3</option>
          <option value="">user4</option>
        </select>
      </form>
    </div>
    <div class="messageBox">
      <div class="messages">

        <div class="bubbleWrapper">
          <div class="inlineContainer own">
            <img class="inlineIcon" src="https://www.pinclipart.com/picdir/middle/205-2059398_blinkk-en-mac-app-store-ninja-icon-transparent.png">
            <div class="ownBubble own">
              The first rule of being a ninja is, 'Do no harm.'
            </div>
          </div><span class="own">08:55</span>
        </div>

        <div class="bubbleWrapper">
          <div class="inlineContainer">
            <img class="inlineIcon" src="https://www.pinclipart.com/picdir/middle/205-2059398_blinkk-en-mac-app-store-ninja-icon-transparent.png">
            <div class="otherBubble other">
              No ninjas!
            </div>
          </div><span class="other">08:41</span>
        </div>
      </div>
      <div class="sendMessage">
        sdgfd
      </div>
    </div>
  </div>
</body>

</html>
