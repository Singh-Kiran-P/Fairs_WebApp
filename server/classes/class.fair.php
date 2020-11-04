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
    $msg = "";
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
  public function checkingZoneSlot($openingSlot, $closingSlot)
  {
    $msg = "";
    return $msg;
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
   * Add Zone slot to database
   *
   * @param [type] $fairId
   * @param [type] $title

   */
  public function addZoneSlot($zoneId, $openingSlot, $closingSlot)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("INSERT INTO zoneslots VALUES (DEFAULT,:zoneId,:openingSlot,:closingSlot)");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
    $query->bindParam(":openingSlot", $openingSlot, PDO::PARAM_STR, 255);
    $query->bindParam(":closingSlot", $closingSlot, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return "";
    } else {
      return $query->errorInfo()[2];
    }
  }




  public  function uploadFiles($files, $id, $type, $ex)
  {
    // Count total files
    $countfiles = count($files['name']);

    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
      $filename = $files['name'][$i];

      // Upload file
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      move_uploaded_file($files['tmp_name'][$i], __DIR__ . '/../uploads/' . $type . '_' . $ex . '/' . $id . '_' . $i . '.' . $ext);
    }
  }

  public  function update()
  {
    //connect to database
    $conn = Database::connect();
  }
}
