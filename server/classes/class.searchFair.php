<?php
include_once "class.database.php";

class SearchFair
{
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
