<?php
include_once "class.database.php";

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
  public function checking($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location)
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


  public  function add($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location, $totImg)
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
  public  function getListOfFairs($cityId)
  {
  }



  public  function uploadFiles($files, $fairId)
  {
    // Count total files
    $countfiles = count($files['name']);

    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
      $filename = $files['name'][$i];

      // Upload file
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      move_uploaded_file($files['tmp_name'][$i], __DIR__ . '/../uploads/fair_Img/' . $fairId . '_' . $i . '.' . $ext);
    }
  }

  public  function update()
  {
    //connect to database
    $conn = Database::connect();
  }
}
