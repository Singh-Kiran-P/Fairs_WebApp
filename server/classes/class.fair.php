<?php
include_once "class.database.php";
include_once 'class.model.fair.php';


/* City class holds the City identity and fuction/methode that are related to users*/
class Fair
{
  /**
   * Check user input
   *
   * @param [date] $startDate
   * @param [date] $endDate
   * @param [time] $openingHour
   * @param [time] $closingHour
   * @return [string] empty string if al is valid else the error msg
   */
  public function checkingAddFair($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location)
  {
    /* check if startdate before current date  */
    if (strtotime($startDate) < mktime(0, 0, 0))
      return "Start date less then current date";

    /* check if enddate before start date  */
    if (strtotime($endDate) <= strtotime($startDate))
      return "End date cannot be smaller then start date";

    /* check if closing hour before opening hour  */
    $closing = new DateTime($closingHour);
    $opening = new DateTime($openingHour);
    if ($closing <= $opening)
      return "Clossing time cannot be less then openning time";

    /* check if there is a other fiar with the same title/startdate/location */
    $msg = $this->_checkIfNotDupplicate($cityId, $title, $startDate, $location);

    return $msg;
  }

  /**
   * Check user input
   *
   * @param [date] $startDate
   * @param [date] $endDate
   * @param [time] $openingHour
   * @param [time] $closingHour
   * @return [string] empty string if al is valid else the error msg
   */
  public function checkingAddZone($fairId, $title, $desc, $open_spots, $location, $attractions)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zones where fair_id=:fairId and title=:title");
    $query->bindParam(":fairId", $fairId, PDO::PARAM_STR, 255);
    $query->bindParam(":title", $title, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      if ($query->rowCount() > 0)
        return "You already have a zone with the same title in this fair";
      else
        return "";
    } else {
      return $query->errorInfo()[2];
    }
  }

  /**
   * Check user input
   *
   * @param [date] $startDate
   * @param [date] $endDate
   * @param [time] $openingHour
   * @param [time] $closingHour
   * @return [string] empty string if al is valid else the error msg
   */
  public function checkingZoneSlot($zoneId, $openingSlot, $closingSlot)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zoneslots where zone_id=:zoneId and opening_slot=:open and closing_slot = :close");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
    $query->bindParam(":open", $openingSlot, PDO::PARAM_STR, 255);
    $query->bindParam(":close", $closingSlot, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      if ($query->rowCount() > 0)
        return "You already have a zoneslot with the same start end end time!";
      else
        return "";
    } else {
      return $query->errorInfo()[2];
    }
  }

  private function _checkIfNotDupplicate($cityId, $title, $startDate, $location)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair where city_id=:city_id and title=:title and start_date=:startDate and location=:location");
    $query->bindParam(":city_id", $cityId, PDO::PARAM_STR, 255);
    $query->bindParam(":title", $title, PDO::PARAM_STR, 255);
    $query->bindParam(":startDate", $startDate, PDO::PARAM_STR, 255);
    $query->bindParam(":location", $location, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      if ($query->rowCount() > 0)
        return "You already have a fair with the same title,startDate and location!";
    } else {
      return $query->errorInfo()[2];
    }

    return "";
  }

  public  function addFair($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location, $totImg)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("INSERT INTO fair VALUES (DEFAULT,:cityId,:title,:desc,:startDate,:endDate,:openingHour,:closingHour,:location,:totImg)");
    $query->bindParam(":cityId", $cityId, PDO::PARAM_STR, 255);
    $query->bindParam(":title", $title, PDO::PARAM_STR, 255);
    $query->bindParam(":desc", $desc, PDO::PARAM_STR, 255);
    $query->bindParam(":startDate", $startDate, PDO::PARAM_STR, 255);
    $query->bindParam(":endDate", $endDate, PDO::PARAM_STR, 255);
    $query->bindParam(":openingHour", $openingHour, PDO::PARAM_STR, 255);
    $query->bindParam(":closingHour", $closingHour, PDO::PARAM_STR, 255);
    $query->bindParam(":location", $location, PDO::PARAM_STR, 255);
    $query->bindParam(":totImg", $totImg, PDO::PARAM_INT, 255);

    if ($query->execute()) {
      return $conn->lastInsertId();
    } else {
      return $query->errorInfo()[2];
    }
  }

  /**
   * Give all the fair that the $city_id has
   *
   * @param [int] $cityId
   * @return [list] list with all fair of the city
   */
  public function getListOfFairs($cityId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair where city_id=:city_id");
    $query->bindParam(":city_id", $cityId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $fairRow = new Fair_Model();
          $fair_id = $row['fair_id'];
          $city_id = $row['city_id'];
          $title = $row['title'];
          $description = $row['description'];
          $start_date = $row['start_date'];
          $end_date = $row['end_date'];
          $opening_hour = $row['opening_hour'];
          $closing_hour = $row['closing_hour'];
          $location = $row['location'];
          $totImg = $row['totimg'];
          $fairRow->setVar($fair_id, $city_id, $title, $description, $start_date, $end_date, $opening_hour, $closing_hour, $location, $totImg);

          array_push($list, $fairRow);
        }
        return $list;
      }
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }

  /**
   * Give the fair with this id
   *
   * @param [int] $fairId
   * @return [fairModel]
   */
  public function getFairModel($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair where fair_id=:fairId");
    $query->bindParam(":fairId", $fairId, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $Fair_Model = new Fair_Model();
        $fair_id = $row['fair_id'];
        $city_id = $row['city_id'];
        $title = $row['title'];
        $description = $row['description'];
        $start_date = $row['start_date'];
        $end_date = $row['end_date'];
        $opening_hour = $row['opening_hour'];
        $closing_hour = $row['closing_hour'];
        $location = $row['location'];
        $totImg = $row['totimg'];
        $Fair_Model->setVar($fair_id, $city_id, $title, $description, $start_date, $end_date, $opening_hour, $closing_hour, $location, $totImg);
        return $Fair_Model;
      }
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }

  /**
   * Get de list of zones
   *
   * @param [type] $zoneId
   * @return array of zones with [zoneId,title]
   */
  public function getFairZones($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zones where fair_id=:fair_id");
    $query->bindParam(":fair_id", $fairId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $zone = array(
            "zoneId" => $row['zone_id'],
            "title" => $row['title']
          );

          array_push($list, $zone);
        }
        return $list;
      }
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }

  /**
   * Add Zone to database
   *
   * @param [type] $fairId
   * @param [type] $title
   * @param [type] $desc
   * @param [type] $open_spots
   * @param [type] $location
   * @param [type] $attractions
   * @param [type] $nImg
   * @param [type] $nVideo
   * @return [int] zone_id
   */
  public function addZone($zoneId, $title, $desc, $open_spots, $location, $attractions, $nImg, $nVideo)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("INSERT INTO zones VALUES (DEFAULT,:zoneId,:title,:description,:location,:open_spots,:attractions,:totImg,:totVideo)");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
    $query->bindParam(":title", $title, PDO::PARAM_STR, 255);
    $query->bindParam(":description", $desc, PDO::PARAM_STR, 255);
    $query->bindParam(":location", $location, PDO::PARAM_STR, 255);
    $query->bindParam(":open_spots", $open_spots, PDO::PARAM_STR, 255);
    $query->bindParam(":attractions", $attractions, PDO::PARAM_STR, 255);
    $query->bindParam(":totImg", $nImg, PDO::PARAM_STR, 255);
    $query->bindParam(":totVideo", $nVideo, PDO::PARAM_STR, 255);


    if ($query->execute()) {
      return $conn->lastInsertId();
    } else {
      return $query->errorInfo()[2];
    }
  }

  /**
   * Add Zone slots to database on every day when the fair is open
   *
   * @param [type] $fairId
   * @param [type] $title

   */
  public function addZoneSlot($zoneId, $openingSlot, $closingSlot, $free_slot, $fairModel)
  {
    //connect to database
    $conn = Database::connect();

    $start_date = new DateTime($fairModel['start_date']);
    $end_date = new DateTime($fairModel['end_date']);
    $nDays = $start_date->diff($end_date)->days;

    for ($i = 0; $i <= $nDays; $i++) {

      $newDate = $start_date->format('Y-m-d');
      $query = $conn->prepare("INSERT INTO zoneslots VALUES (DEFAULT,:zoneId,:openingSlot,:closingSlot,:free_slot,:date)");
      $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
      $query->bindParam(":openingSlot", $openingSlot, PDO::PARAM_STR, 255);
      $query->bindParam(":closingSlot", $closingSlot, PDO::PARAM_STR, 255);
      $query->bindParam(":date", $newDate, PDO::PARAM_STR, 255);
      $query->bindParam(":free_slot", $free_slot, PDO::PARAM_STR, 255);

      $start_date->modify('+1 day');
      if (!$query->execute()) {
        return $query->errorInfo()[2];
      }
    }

    return "Time slot succesfully added!";
  }

  public function getAllZones($fairId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zones where fair_id = :fairId order BY zone_id DESC;");
    $query->bindParam(":fairId", $fairId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          array_push($list, ["zone_id" => $row['zone_id'], "freeSlots" => $row['open_spots']]);
        }
        return $list;
      }
    } else {
      return $query->errorInfo()[2];
    }
  }

  /**
   * Get de zones info
   *
   * @param [type] $zoneId
   * @return array of zone info
   */
  public function getZone($zoneId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zones where zone_id=:zone_id");
    $query->bindParam(":zone_id", $zoneId, PDO::PARAM_STR, 255);


    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $zoneInfo = array(
          "zoneId" => $row['zone_id'],
          "fairId" => $row['fair_id'],
          "title" => $row['title'],
          "description" => $row['description'],
          "attractions" => $row['attractions'],
          "location" => $row['location'],
          "totimg" => $row['totimg'],
          "totvideo" => $row['totvideo'],
          "open_spots" => $row['open_spots']
        );


        return $zoneInfo;
      }
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }
  /**
   * Get de date by zoneId
   *
   * @param [type] $zoneId
   * @return array of zones with [zoneId,title]
   */
  public function getZonesDate($zoneId)
  {
    //connect to database
    $conn = Database::connect();
    /*
    $query = $conn->prepare("select zone_id from zones where fair_id = :fairId order BY zone_id DESC;");
    $query->bindParam(":fairId", $fairId, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $zoneId = $row['zone_id'];
      }
    } else {
      return $query->errorInfo()[2];
    }
     */
    $query = $conn->prepare("select DISTINCT start_date from zoneslots where zone_id =:zoneId;");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          array_push($list, $row['start_date']);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    return  $list;
  }


  public function updateDbFileCount($id, $i, $v, $table)
  {
    //connect to database
    $conn = Database::connect();


    if ($table == "fair") {
      $query = $conn->prepare("UPDATE fair SET totimg=:imgCount WHERE fair_id=:id");

      $query->bindParam(":id", $id, PDO::PARAM_STR, 255);
      $query->bindParam(":imgCount", $i, PDO::PARAM_STR, 255);
    }
    if ($table == "zones") {
      $query = $conn->prepare("UPDATE zones SET totimg=:imgCount,totvideo=:vCount WHERE zone_id=:id");

      $query->bindParam(":id", $id, PDO::PARAM_STR, 255);
      $query->bindParam(":imgCount", $i, PDO::PARAM_STR, 255);
      $query->bindParam(":vCount", $v, PDO::PARAM_STR, 255);
    }




    if (!$query->execute()) {
      return $query->errorInfo()[2];
    }
  }

  /**
   * This function gets the zone time slots from the database and echo's html table rows
   * So that the front-end AJAX can pick it up.
   * @param [int] $zoneId
   * @echo html table rows
   * @return errorMsg
   */
  public function showZoneTimeSlots($zoneId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zoneslots where zone_id = :zoneId;");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);

    $html_out = "";

    if ($query->execute()) {
      if ($query->rowCount() > 0) { // There is atleast 1 row of timeslots
        while ($row = $query->fetch()) {
          $slotDate = $row['start_date'];
          $opening_slot = $row['opening_slot'];
          $closing_slot = $row['closing_slot'];
          $openSlots = $row['free_slots'];

          $html_out .= "<tr>";
          $html_out .= "<td>$slotDate</td>";
          $html_out .= "<td>$opening_slot</td>";
          $html_out .= "<td>$closing_slot</td>";
          $html_out .= "<td>$openSlots</td>";
          $html_out .= "</tr>";
        }

        // echo html table rows
        return $html_out;
      } else { // No timeslots for this zone in de database
        return "No timeslots for this zone in de database";
      }
    } else {
      return $query->errorInfo()[2];
    }
  }

  /**
   * This function gets the zone time slots from the database by date and echo's html table rows
   * So that the front-end AJAX can pick it up.
   * @param [int] $zoneId
   * @param date $date
   * @echo html table rows
   * @return errorMsg
   */
  public function showZoneTimeSlotsByDate($zoneId, $date)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from zoneslots where zone_id = :zoneId and start_date = :date;");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
    $query->bindParam(":date", $date, PDO::PARAM_STR, 255);

    $html_out = "";

    if ($query->execute()) {
      if ($query->rowCount() > 0) { // There is atleast 1 row of timeslots
        while ($row = $query->fetch()) {
          $slotDate = $row['start_date'];
          $opening_slot = $row['opening_slot'];
          $closing_slot = $row['closing_slot'];
          $openSlots = $row['free_slots'];

          $html_out .= "<tr>";
          $html_out .= "<td>$slotDate</td>";
          $html_out .= "<td>$opening_slot</td>";
          $html_out .= "<td>$closing_slot</td>";
          $html_out .= "<td>$openSlots</td>";
          $html_out .= "</tr>";
        }
        // echo html table rows
        echo $html_out;
      } else { // No timeslots for this zone in de database
        echo "No timeslots for this zone in de database";
      }
    } else {
      return $query->errorInfo()[2];
    }
  }
}
