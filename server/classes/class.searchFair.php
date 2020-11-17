<?php
/* https://www.kodingmadesimple.com/2016/02/get-json-from-url-in-php.html#:~:text=The%20php%20function%20file_get_contents(%24,you%20can%20parse%20json%20response. */

include_once "class.database.php";


class SearchFair
{
  /**
   * GeoJSON for mapBox
   *
   * @return void
   */
  public function getGeoJsonOfAllFairs()
  {

    $geoJson = array(
      "type" => "FeatureCollection",
      "features" => []
    );

    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from fair");

    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {

          $fair_id = $row['fair_id'];
          $city_id = $row['city_id'];
          $title = $row['title'];
          $description = $row['description'];
          $start_date = $row['start_date'];
          $end_date = $row['end_date'];
          $opening_hour = $row['opening_hour'];
          $closing_hour = $row['closing_hour'];
          $location = $row['location'];

          $coordinates = $this->getLocationCordinaats($location);
          if (is_string($coordinates)) {
            continue;
          }
          // create feature
          $feature = array(
            "type" => "Feature",
            "properties" => [
              "title" => $title,
              "description" =>
              '<a href="../fairOverView.php?fair_id=' . $fair_id . '" target="_blank">Go to fair info</a> This fair is going to take place from ' . $start_date . ' to ' . $end_date . '. These are the hourser ' . $opening_hour . ' to ' . $closing_hour,
              "icon" =>  "amusement-park"
            ],
            "geometry" => [
              "type" => "Point",
              "coordinates" => $coordinates
            ]
          );

          array_push($geoJson['features'], $feature);
        }
      }
      return $geoJson;
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }
  private function getLocationCordinaats($place)
  {
    // get the env variables
    require __DIR__ . '/../config/config.php';
    //set map api url
    $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . $place . ".json?access_token=" . $mapBoxKey . "&limit=1";

    //call api
    $json = file_get_contents($url);
    $json = json_decode($json);
    if (count($json->features) > 0) {
      $lat = $json->features[0]->center[0];
      $lng = $json->features[0]->center[1];
      return [$lat, $lng];
    } else {
      return "Location not found!";
    }
  }

  public function searchByName($title)
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
          );

          array_push($list, $fair);
        }
        return $list;
      }
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }

  public function totCountFiles($id, $table)
  {
    //connect to database
    $conn = Database::connect();

    if ($table == "fair") {
      $query = $conn->prepare("select * from fair where fair_id = :id");
      $query->bindParam(":id", $id, PDO::PARAM_STR, 255);

      if ($query->execute()) {
        $row = $query->fetch();
        return $row['totimg'];
      } else {
        return $query->errorInfo()[2];
      }
    }
    if ($table == "zones") {
      $query = $conn->prepare("select * from zones where zone_id = :id");
      $query->bindParam(":id", $id, PDO::PARAM_STR, 255);

      if ($query->execute()) {
        $row = $query->fetch();
        $out = array(
          "totimg" => $row['totimg'],
          "totvideo" => $row['totvideo'],
        );
        return $out;
      } else {
        return $query->errorInfo()[2];
      }
    }

    return NULL;
  }
  public function totVideo($id, $table)
  {
    //connect to database
    $conn = Database::connect();
    $query = $conn->prepare("select * from :table where upper(title) LIKE upper(:title)");

    if ($table == "fair") {
      $query = $conn->prepare("select * from fair where fair_id = :id)");

      $query->bindParam(":id", $id, PDO::PARAM_STR, 255);
    }
    if ($table == "zones") {
      $query = $conn->prepare("select * from zones where zone_id = :id)");
      $query->bindParam(":id", $id, PDO::PARAM_STR, 255);
    }

    if ($query->execute()) {
      $row = $query->fetch();
      $out = array(
        $row['totimg'],
        $row['totvideo']
      );
      return $row['totimg'];
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }
}
