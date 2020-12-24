<?php
include_once "class.database.php";
include_once "class.upload.php";

/* Admin class holds the Admin fuction and methodes*/
class Admin
{
  /**
   * Get the City Data
   *
   * @param [int] $cityId
   * @return List with the data
   */
  public function getCityData($cityId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from accounts,city where  city.user_id= accounts.user_id and accounts.user_id = :id");
    $query->bindParam(":id", $cityId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $list = [
          "name" => $row['name'],
          "username" => $row['username'],
          "email" => $row['email'],
          "type" => $row['type'],
          "created_on" => $row['created_on'],
          "short_description" => $row['short_description'],
          "telephone" => $row['telephone']
        ];
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }

  /**
   * Update City data
   *
   * @param int $cityId
   * @param list $data
   * @return text msg
   */
  public function updateCityData($cityId, $data)
  {
    $res = $this->_checkAccountData($data);
    if ($res == '') { //no error in checking

      //connect to database
      $conn = Database::connect();

      /* Update Accounts table */
      $query = $conn->prepare("UPDATE accounts SET name=:name,username=:username,email=:email WHERE user_id=:id");

      $query->bindParam(":id", $cityId, PDO::PARAM_STR, 255);
      $query->bindParam(":name", $data['name'], PDO::PARAM_STR, 255);
      $query->bindParam(":username", $data['username'], PDO::PARAM_STR, 255);
      $query->bindParam(":email", $data['email'], PDO::PARAM_STR, 255);

      if (!$query->execute()) {
        return $query->errorInfo()[2];
      } else { //Update City table

        $query = $conn->prepare("UPDATE city SET telephone=:telephone,short_description=:short_description WHERE city_id=:id");

        $query->bindParam(":id", $cityId, PDO::PARAM_STR, 255);
        $query->bindParam(":telephone", $data['telephone'], PDO::PARAM_STR, 255);
        $query->bindParam(":short_description", $data['description'], PDO::PARAM_STR, 255);
        if (!$query->execute())
          return $query->errorInfo()[2];
      }

      return "";
    } else {
      return $res;
    }
  }

  /**
   * Delete City, if there is no city the fairs of this city are also deleted and the users notified.
   *
   * @param [type] $cityId
   * @return void
   */
  public function deleteCity($cityId)
  {
    $allFairIds = $this->_getAllFairIdsOfCity($cityId);

    //delete all fair of this city
    foreach ($allFairIds as $item) {
      $this->deleteFair($item['fairId'], $item['title']);
    }

    //delete city
    //connect to database
    $conn = Database::connect();

    /* Delete fair */
    $query = $conn->prepare("DELETE FROM accounts WHERE user_id =(select user_id from city where city_id =:id)");
    $query->bindParam(":id", $cityId, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return true;
    } else {
      return false;
    }
  }

  private function _getAllFairIdsOfCity($cityId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair where city_id = :id");
    $query->bindParam(":id", $cityId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $data = [
            "title" => $row['title'],
            "fairId" => $row['fair_id'],
          ];
          array_push($list, $data);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }

  /**
   * Get fair Data from database.
   *
   * @param int $fairId
   * @return list with data
   */
  public function getFairData($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair where fair_id = :id");
    $query->bindParam(":id", $fairId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $list = [
          "title" => $row['title'],
          "description" => $row['description'],
          "start_date" => $row['start_date'],
          "end_date" => $row['end_date'],
          "opening_hour" => $row['opening_hour'],
          "closing_hour" => $row['closing_hour'],
          "location" => $row['location']
        ];
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }



  /**
   * Update Fair data TO-DO
   *  - update all zoneSlotes opening_slot and closing_slot  and star_date
   *
   * @param [type] $fairId
   * @param [type] $data
   * @return void
   */
  public function updateFairData($fairId, $data)
  {
    $res = $this->_checkFairData($data);
    if ($res == '') { //no error in checking

      //connect to database
      $conn = Database::connect();

      /* Update Accounts table */
      $query = $conn->prepare("UPDATE accounts SET name=:name,username=:username,email=:email,type=:type WHERE user_id=:id");

      $query->bindParam(":id", $fairId, PDO::PARAM_STR, 255);
      $query->bindParam(":name", $data['name'], PDO::PARAM_STR, 255);
      $query->bindParam(":username", $data['username'], PDO::PARAM_STR, 255);
      $query->bindParam(":email", $data['email'], PDO::PARAM_STR, 255);
      $query->bindParam(":type", $data['type'], PDO::PARAM_STR, 255);

      if (!$query->execute())
        return $query->errorInfo()[2];

      return "Updated successfully";
    } else {
      return $res;
    }
  }

  /**
   * Helper function for updateFairData
   *
   * @param [type] $data
   * @return void
   */
  private function _checkFairData($data)
  {
    /* check if not emptys */
    if ($data['title'] == "")
      return "Title connot be empty";
    if ($data['description'] == "")
      return "Title connot be empty";
    if ($data['location'] == "")
      return "Title connot be empty";


    /* check if startdate before current date  */
    if (strtotime($data['start_date']) < mktime(0, 0, 0))
      return "Start date less then current date";

    /* check if enddate before start date  */
    if (strtotime($data['end_date']) <= strtotime($data['start_date']))
      return "End date cannot be smaller then start date";

    /* check if closing hour before opening hour  */
    $closing = new DateTime($data['closingHour']);
    $opening = new DateTime($data['openingHour']);
    if ($closing <= $opening)
      return "Clossing time cannot be less then openning time";
  }

  /**
   * Delete Fair
   *  - delete all img of this fair from the server
   *  - delete all img and videos of all the zones of this fair
   *  - notify all visitors who had an reservation (using notity table)
   *
   *
   * @param int $fairId
   * @return text msg
   */
  public function deleteFair($fairId, $title)
  {

    $reservationUsers = $this->_getAllReservationUsers($fairId);
    $allZonesIds = $this->_getAllZonesIds($fairId);
    $nFairImg = $this->_getNOfFairImg($fairId);
    //connect to database
    $conn = Database::connect();

    /* Delete fair */
    $query = $conn->prepare("DELETE FROM fair WHERE fair_id=:id ");
    $query->bindParam(":id", $fairId, PDO::PARAM_STR, 255);

    if ($query->execute()) {

      //Notify visitors
      $this->_notifyVisitors($reservationUsers, $title);

      //Delete all img's en video's
      $this->_deleteAllMedia($fairId, "fair_img", $nFairImg);

      foreach ($allZonesIds  as $zone) {
        $this->_deleteAllMedia($zone['id'], "zone_img", $zone['nZoneImg']);
        $this->_deleteAllMedia($zone['id'], "zone_video", $zone['nZoneVideo']);
      }
    } else {
      return false;
    }

    return true;
  }

  private function _getNOfFairImg($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair where fair_id = :id");
    $query->bindParam(":id", $fairId, PDO::PARAM_STR, 255);

    $n = 0;
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $n = $row['totimg'];
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $n;
  }

  /**
   * Helper fuction for DeleteFair
   * Get all visitors that have reservation for this fair
   * @param int $fairId
   * @return list with user ids
   */
  private function _getAllReservationUsers($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from reservations where fair_id = :id");
    $query->bindParam(":id", $fairId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          array_push($list, ['user_id' => $row['user_id']]);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }

  /**
   * Helper fuction for DeleteFair
   * Get all zone ids for this fair
   * @param int $fairId
   * @return list with zone ids
   */
  private function _getAllZonesIds($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair,zones where fair.fair_id = :fair_id and zones.fair_id = fair.fair_id");
    $query->bindParam(":fair_id", $fairId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          array_push($list, ['id' => $row['zone_id'], 'nZoneImg' => $row['totimg'], 'nZoneVideo' => $row['totvideo']]);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }

  /**
   * Helper function for DeleteFair
   *
   * @param list $listOfUsers
   * @param text $fairTitle
   * @return void
   */
  private function _notifyVisitors($listOfUsers, $fairTitle)
  {
    //connect to database
    $conn = Database::connect();

    foreach ($listOfUsers as $userId) {
      $query = $conn->prepare("INSERT INTO notifications VALUES (DEFAULT,:userId,:msg)");
      $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
      $msg = "The admin decided to delete " . $fairTitle . ". We had to cancel all reservations for this fair.";
      $query->bindParam(":msg", $msg, PDO::PARAM_STR, 255);
      $query->execute();
    }
  }

  /**
   * Helper function for DeleteFair
   * Delete All imgs and video's
   * @param list $listOfUsers
   * @param text $fairTitle
   * @return void
   */
  private function _deleteAllMedia($id, $folder, $n)
  {
    for ($i = 0; $i < $n; $i++) {
      $filename = Upload::getUploadedFilePath($id . "_" . $i, $folder);
      try {
        unlink(__DIR__ . '/../uploads/' . $folder . "/" . $filename);
      } catch (\Throwable $th) {
        return "error";
      }
    }
  }

  /**
   * Get the Visitor Data
   *
   * @param [int] $cityId
   * @return List with the data
   */
  public function getVisitorData($visitorId)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("select * from accounts where accounts.user_id = :id");
    $query->bindParam(":id", $visitorId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $list = [
          "name" => $row['name'],
          "username" => $row['username'],
          "email" => $row['email'],
          "type" => $row['type'],
          "created_on" => $row['created_on']
        ];
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }

  /**
   * Update City data
   *
   * @param int $cityId
   * @param list $data
   * @return text msg
   */
  public function updateVisitorData($visitorId, $data)
  {
    $res = $this->_checkAccountData($data);
    if ($res == '') { //no error in checking

      //connect to database
      $conn = Database::connect();

      /* Update Accounts table */
      $query = $conn->prepare("UPDATE accounts SET name=:name,username=:username,email=:email WHERE user_id=:id");

      $query->bindParam(":id", $visitorId, PDO::PARAM_STR, 255);
      $query->bindParam(":name", $data['name'], PDO::PARAM_STR, 255);
      $query->bindParam(":username", $data['username'], PDO::PARAM_STR, 255);
      $query->bindParam(":email", $data['email'], PDO::PARAM_STR, 255);

      if (!$query->execute())
        return $query->errorInfo()[2];

      return "";
    } else {
      return $res;
    }
  }

  /**
   * Helper fuction for updateVisitorData and updateCityData
   *
   * @param list $data
   * @return text msg
   */
  private function _checkAccountData($data)
  {
    /* check if not emptys */
    if ($data['name'] == "")
      return "Name connot be empty";
    if ($data['username'] == "")
      return "Username connot be empty";
    if ($data['email'] == "")
      return "Email connot be empty";

    return '';
  }

  /**
   * Delete Visitor
   *
   * @param int $visitorId
   * @return boolean true if visitor delete successfully
   */
  public function deleteVisitor($visitorId)
  {
    //connect to database
    $conn = Database::connect();

    /* Update Accounts table */
    $query = $conn->prepare("DELETE FROM accounts WHERE user_id=:id and type='visitor'");
    $query->bindParam(":id", $visitorId, PDO::PARAM_STR, 255);

    if (!$query->execute())
      return $query->errorInfo()[2];

    return true;
  }

  /* Admin Main search functions */
  public static function getAllFairs($title)
  {
    //connect to database
    $conn = Database::connect();
    $query = $conn->prepare("select * from fair where upper(title) LIKE upper(:title)");
    $parm = '%' . $title . '%';
    $query->bindParam(":title", $parm, PDO::PARAM_STR, 255);


    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $fair = array(
            "fairId" => $row['fair_id'],
            "title" => $row['title'],
            "start_date" => $row['start_date'],
            "end_date" => $row['end_date'],
          );

          array_push($list, $fair);
        }
      }
    }
    return $list;
  }
  public static function getAllVisitors($name)
  {
    //connect to database
    $conn = Database::connect();
    $query = $conn->prepare("select * from accounts where upper(name) LIKE upper(:name) and type ='visitor'");
    $parm = '%' . $name . '%';
    $query->bindParam(":name", $parm, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $user = array(
            "user_id" => $row['user_id'],
            "name" => $row['name'],
            "username" => $row['username'],
          );

          array_push($list, $user);
        }
      }
    }
    return $list;
  }
  public static function getAllCity($name)
  {
    //connect to database
    $conn = Database::connect();
    $query = $conn->prepare("select * from accounts where upper(name) LIKE upper(:name) and type='city'");
    $parm = '%' . $name . '%';
    $query->bindParam(":name", $parm, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $user = array(
            "user_id" => $row['user_id'],
            "name" => $row['name'],
            "username" => $row['username'],
          );

          array_push($list, $user);
        }
      }
    }
    return $list;
  }
}
