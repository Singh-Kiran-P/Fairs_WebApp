<?php
/* https://www.kodingmadesimple.com/2016/02/get-json-from-url-in-php.html#:~:text=The%20php%20function%20file_get_contents(%24,you%20can%20parse%20json%20response.

https://www.intelliwolf.com/find-nearest-location-from-array-of-coordinates-php/
*/

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

          $coordinates = $this->getLocationCoordinates($location);
          if (is_string($coordinates)) {
            continue;
          }
          // create feature
          $feature = array(
            "type" => "Feature",
            "properties" => [
              "title" => $title,
              "fair_id" => $fair_id,
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
  public function getLocationCoordinates($place)
  {
    // get the env variables
    require __DIR__ . '/../config/config.php';
    //set map api url
    $url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" . $place . ".json?access_token=" . $mapBoxKey . "&limit=1&country=be";

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

  /**
   * Gives back a list of fairs by period
   *
   * @param [type] $d1 startDate
   * @param [type] $d2 endDate
   * @return List
   */
  public function searchFairByPeriod($d1, $d2)
  {
    //connect to database
    $conn = Database::connect();
    $query = $conn->prepare("select * from fair where start_date >= :d1 and end_date <=:d2");
    $query->bindParam(":d1", $d1, PDO::PARAM_STR, 255);
    $query->bindParam(":d2", $d2, PDO::PARAM_STR, 255);


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
  }

  /**
   * Return a array sorted by location
   *
   * @param [type] $baseLocation
   * @return void
   */
  public function searchSortFairsByLocation($baseLocation, $closest)
  {
    //get all Fairs
    $allFairs = $this->getGeoJsonOfAllFairs()['features'];

    // get baseLocation coordinates
    $base_correction = $this->getLocationCoordinates($baseLocation);

    $distances = array();

    foreach ($allFairs as $features) {
      $a = $base_correction[0] - $features['geometry']['coordinates'][0];
      $b = $base_correction[1] - $features['geometry']['coordinates'][1];
      $distance = sqrt(($a ** 2) + ($b ** 2));
      $fair = array(
        "fairId" => $features['properties']['fair_id'],
        "title" =>  $features['properties']['title'],
        "distance" =>  $distance
      );
      array_push($distances, $fair);
    }

    $cmp_closest = function (array $a, array $b) {
      if ($a['distance'] < $b['distance']) {
        return -1;
      } else if ($a['distance'] > $b['distance']) {
        return 1;
      } else {
        return 0;
      }
    };

    $cmp_farthest = function (array $a, array $b) {
      if ($a['distance'] > $b['distance']) {
        return -1;
      } else if ($a['distance'] < $b['distance']) {
        return 1;
      } else {
        return 0;
      }
    };

    if ($closest)
      usort($distances, $cmp_closest);
    else
      usort($distances, $cmp_closest);

    return $distances;
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
