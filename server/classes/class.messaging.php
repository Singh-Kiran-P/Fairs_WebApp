<?php
include_once "class.database.php";
include_once __DIR__ . "/../preventions/func.xss.php";



/* Messaging class gets the messages of the users and the logic to do that*/

class Messaging
{

  /**
   * Get the messages
   *
   * @param [type] $userId_sender
   * @param [type] $userId_reciever
   * @return List with messages
   * @return if error or no message return string with msg else ''
   */
  public function getMessages($msgFrom, $msgTo)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare(" select * from messaging
                              where
                              (msgFrom = :msgFrom and msgTo = :msgTo)
                              OR (msgFrom = :msgTo and msgTo = :msgFrom)

                              ORDER BY send_datetime ASC ");
    $query->bindParam(":msgFrom", $msgFrom, PDO::PARAM_STR, 255);
    $query->bindParam(":msgTo", $msgTo, PDO::PARAM_STR, 255);

    $listWithMessages = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $singleMessage = array(
            'msgFrom' => $row['msgfrom'],
            'msgTo' => $row['msgto'],
            'send_datetime' => $row['send_datetime'],
            'message' => $row['message']
          );

          array_push($listWithMessages, $singleMessage);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }
    return $listWithMessages;
  }

  /**
   * Get msg that are not openend to push a notification to font-end user
   *  for Ajax
   * @param [int] $userId
   */
  public function getUnOpenendMsg($userId, $val)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select accounts.user_id, count(*) as msgCount,accounts.name from messaging,accounts where msgto = :msgTo and msgFrom = accounts.user_id  and openend = false and showed = :showed group by accounts.name,accounts.user_id");
    $query->bindParam(":msgTo", $userId, PDO::PARAM_STR, 255);
    $query->bindParam(":showed", $val, PDO::PARAM_STR, 255);

    $Json = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $singleMessage = array(
            'msgCount' => $row['msgcount'],
            'msgFrom' => $row['name'],
            'user_id' => $row['user_id'],
          );
          array_push($Json, $singleMessage);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    if ($val == "false") {
      //echo for AJAX
      echo json_encode($Json);
      $this->setShowed($userId);
    }
    return $Json;
  }

  private function setShowed($id)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("update messaging set showed = true where msgTo=:userId;");
    $query->bindParam(":userId", $id, PDO::PARAM_STR, 255);

    if (!$query->execute()) {
      return $query->errorInfo()[2];
    }
  }

  public function msgOpenend($userId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("update messaging set openend = true where msgfrom=:userId;");
    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);

    if (!$query->execute()) {
      return $query->errorInfo()[2];
    }
  }
  /**
   * Send Msg
   *
   * @param [type] $msgFrom
   * @param [type] $msgTo
   * @return void
   */
  public function sendMsg($msgFrom, $msgTo, $msg)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare(" insert into messaging
                              VALUES
                              (DEFAULT,:msgFrom,:msgTo,:msg,current_timestamp,DEFAULT);");
    $query->bindParam(":msgFrom", $msgFrom, PDO::PARAM_STR, 255);
    $query->bindParam(":msgTo", $msgTo, PDO::PARAM_STR, 255);
    $query->bindParam(":msg", $msg, PDO::PARAM_STR, 255);


    if ($query->execute()) {
      return '';
    } else {
      return $query->errorInfo()[2];
    }
  }

  public function  buildMsgBox($listWithMessages, $msgFrom, $msgTo)
  {
    $outHTML = '';
    foreach ($listWithMessages as $item) {

      if ($item['msgFrom'] == $msgFrom) {
        $currentUserMsgBubble = '';
        $currentUserMsgBubble .= '<div class="bubbleWrapper">';
        $currentUserMsgBubble .= '<div class="inlineContainer own">';
        $currentUserMsgBubble .= '<img class="inlineIcon" src="https://www.pinclipart.com/picdir/middle/205-2059398_blinkk-en-mac-app-store-ninja-icon-transparent.png">';
        $currentUserMsgBubble .= '<div class="ownBubble own">';
        $currentUserMsgBubble .=  _e($item['message']);
        $currentUserMsgBubble .= '</div>';
        $currentUserMsgBubble .= '</div><span class="own">' . $item['send_datetime'] . '</span>';
        $currentUserMsgBubble .= '</div>';
        $outHTML .= $currentUserMsgBubble;
      } else {
        $otherUserMsgBubble = '';
        $otherUserMsgBubble .= '<div class="bubbleWrapper">';
        $otherUserMsgBubble .= '<div class="inlineContainer">';
        $otherUserMsgBubble .= '<img class="inlineIcon" src="https://www.pinclipart.com/picdir/middle/205-2059398_blinkk-en-mac-app-store-ninja-icon-transparent.png">';
        $otherUserMsgBubble .= '<div class="otherBubble other">';
        $otherUserMsgBubble .= _e($item['message']);
        $otherUserMsgBubble .= '</div>';
        $otherUserMsgBubble .= '</div><span class="other">' . $item['send_datetime'] . '</span>';
        $otherUserMsgBubble .= '</div>';
        $outHTML .= $otherUserMsgBubble;
      }
    }

    return $outHTML;
  }
}
