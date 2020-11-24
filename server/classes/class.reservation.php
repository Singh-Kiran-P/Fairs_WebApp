<?php
include_once "class.database.php";
include_once "class.fair.php";

class Reservation
{
  public function checkSpot($zoneId, $reservationDate, $reservationTimeSlot, $reservationPeople)
  {

    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("SELECT * FROM zoneslots WHERE zone_id = :zoneId and ((opening_slot < closing_slot AND :opening_slot BETWEEN opening_slot AND closing_slot)
    OR
    (closing_slot < opening_slot AND :opening_slot < opening_slot AND :opening_slot < closing_slot)
    OR
    (closing_slot < opening_slot AND :opening_slot > opening_slot))  and start_date =:resDate ");

    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
    $query->bindParam(":opening_slot", $reservationTimeSlot, PDO::PARAM_STR, 255);
    $query->bindParam(":resDate", $reservationDate, PDO::PARAM_STR, 255);


    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();

        if ($row['free_slots'] - $reservationPeople >= 0) {
          return ['zoneslot_id' => $row['zoneslot_id'], 'free' => $row['free_slots'], 'msg' => ''];
        } else {
          return "One this" . $reservationDate . " and" . $reservationTimeSlot . "is reservation not available";
        }
      } else {
        return ['msg' => "Cannot reserver on this date and timeslot"];
      }
    } else {
      return $query->errorInfo()[2];
    }
  }

  public function updateZoneslot($zoneSlot_id, $freeSlots, $reservationPeople)
  {

    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("UPDATE zoneslots SET free_slots=:vCount WHERE zoneslot_id=:id");
    $n = $freeSlots - $reservationPeople;
    $query->bindParam(":id", $zoneSlot_id, PDO::PARAM_STR, 255);
    $query->bindParam(":vCount", $n, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return ['msg' => ""];
    } else {
      return ['msg' => $query->errorInfo()[2]];
    }
  }

  public function book($userId, $fairId, $zonneSlotId,$reservationPeople)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("INSERT INTO reservations VALUES (DEFAULT,:userId,:zonneSlotId,:fairId,:nPeople,true,null,null)");
    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
    $query->bindParam(":zonneSlotId", $zonneSlotId, PDO::PARAM_STR, 255);
    $query->bindParam(":nPeople", $reservationPeople, PDO::PARAM_STR, 255);
    $query->bindParam(":fairId", $fairId, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return ['msg' => ""];
    } else {
      return ['msg' => $query->errorInfo()[2]];
    }
  }

  public function getReservations($userId)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("select r.reservation_id, r.nOfPeople,zs.start_date,f.fair_id,z.zone_id, f.title as fair_title,z.title as zone_tile,zs.opening_slot,zs.closing_slot from zones z,reservations r,zoneslots zs,fair f where r.fair_id = f.fair_id and r.zoneslot_id = zs.zoneslot_id and zs.zone_id = z.zone_id and r.user_id = :userId");

    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);

    $resverationList = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $reservationInfo = [
            "reservation_id" => $row['reservation_id'],
            "nOfPeople" => $row['nofpeople'],
            "fair_id" => $row['fair_id'],
            "zone_id" => $row['zone_id'],
            "date" => $row['start_date'],
            "fairTitle" => $row['fair_title'],
            "zoneTitle" => $row['zone_tile'],
            "opening_slot" => $row['opening_slot'],
            "closing_slot" => $row['closing_slot'],
          ];
          array_push($resverationList, $reservationInfo);
        }
        return $resverationList;
      }
    } else {
      return $query->errorInfo()[2];
    }
  }
}
