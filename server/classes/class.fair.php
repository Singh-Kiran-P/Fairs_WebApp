<?php
include_once "class.database.php";

/* City class holds the City identity and fuction/methode that are related to users*/
class Fair
{
  public static function add($cityId, $title, $desc, $startDate, $endDate, $openingHour, $closingHour, $location,$totImg)
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

  public static function uploadFiles($files,$fairId)
  {
    // Count total files
    $countfiles = count($files['name']);

    // Looping all files
    for ($i = 0; $i < $countfiles; $i++) {
      $filename = $files['name'][$i];

      // Upload file
      $ext = pathinfo($filename, PATHINFO_EXTENSION);
      move_uploaded_file($files['tmp_name'][$i], __DIR__.'/../uploads/fair_Img/'.$fairId.'_'.$i.'.'. $ext);
    }
  }

  public static function update()
  {
    //connect to database
    $conn = Database::connect();
  }
}
