<?php
/* https://www.kodingmadesimple.com/2016/02/get-json-from-url-in-php.html#:~:text=The%20php%20function%20file_get_contents(%24,you%20can%20parse%20json%20response.

https://www.intelliwolf.com/find-nearest-location-from-array-of-coordinates-php/
*/

include_once "class.database.php";
include_once __DIR__ . "/../preventions/func.xss.php";


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
              "location" => $location,
              "description" =>
              '<a href="../fairOverView.php?fair_id=' . $fair_id . '" target="_blank">Go to fair info</a><br> This fair is going to take place from <br> ' . $start_date . '&nbsp;&nbsp;&nbsp;&nbsp;TO&nbsp;&nbsp;&nbsp;&nbsp;' . $end_date . '<br> Opening Hours: <br>' . $opening_hour . '&nbsp;&nbsp;&nbsp;&nbsp;TO&nbsp;&nbsp;&nbsp;&nbsp;' . $closing_hour,
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

  public function searchByName($title, $oldFairs)
  {
    //connect to database
    $conn = Database::connect();
    if ($oldFairs)
      $query = $conn->prepare("select * from fair where upper(title) LIKE upper(:title) and end_date >= CURRENT_DATE");
    else
      $query = $conn->prepare("select * from fair where upper(title) LIKE upper(:title) and end_date < CURRENT_DATE");

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
    $query = $conn->prepare(
      "select * from fair " .
        "Where" .
        "(start_date BETWEEN :d1 AND :d2) OR " .
        "(end_date BETWEEN :d1 AND :d2) OR " .
        "(start_date <= :d1 AND end_date >= :d2)"
    );
    $query->bindParam(":d1", $d1, PDO::PARAM_STR, 255);
    $query->bindParam(":d2", $d2, PDO::PARAM_STR, 255);


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
      $lat1 = $base_correction[0];
      $lat2 = $features['geometry']['coordinates'][0];
      $lng1 = $base_correction[1];
      $lng2 = $features['geometry']['coordinates'][1];

      $distance = $this->_getDistance($lat1, $lng1, $lat2, $lng2, "K");

      $fair = array(
        "fairId" => $features['properties']['fair_id'],
        "title" =>  $features['properties']['title'],
        "location" =>  $features['properties']['location'],
        "distance_txt" =>  round($distance, 5) . " km",
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
      usort($distances, $cmp_farthest);

    return $distances;
  }

  /**
   * This function is copied from https://www.geodatasource.com/developers/php
   *
   * @param [type] $lat1
   * @param [type] $lng1
   * @param [type] $lat2
   * @param [type] $lng2
   * @param [type] $unit
   * @return void
   */
  public function _getDistance($lat1, $lng1, $lat2, $lng2, $unit)
  {
    if (($lat1 == $lat2) && ($lng1 == $lng2)) {
      return 0;
    } else {
      $theta = $lng1 - $lng2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);

      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
        return ($miles * 0.8684);
      } else {
        return $miles;
      }
    }
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

    return 0;
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
