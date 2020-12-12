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
          return ['zoneslot_id' => $row['zoneslot_id'], 'msg' => "Can not book on " . $reservationDate . " booking is not available. "];
        }
      } else {
        return ['msg' => "You can not book on this date and timeslot"];
      }
    } else {
      return $query->errorInfo()[2];
    }
  }

  public function updateZoneslot($zoneSlot_id,$reservationPeople, $add = false)
  {

    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("UPDATE zoneslots SET free_slots=:vCount WHERE zoneslot_id=:id");
    if ($add)
      $query = $conn->prepare("UPDATE zoneslots SET free_slots=free_slots+:vCount WHERE zoneslot_id=:id");
    else
      $query = $conn->prepare("UPDATE zoneslots SET free_slots=free_slots-:vCount WHERE zoneslot_id=:id");

    $query->bindParam(":id", $zoneSlot_id, PDO::PARAM_STR, 255);
    $query->bindParam(":vCount", $reservationPeople, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return ['msg' => ""];
    } else {
      return ['msg' => $query->errorInfo()[2]];
    }
  }

  public function book($userId, $fairId, $zonneSlotId, $reservationPeople)
  {
    //connect to database
    $conn = Database::connect();

    $this->removeFromWaitingList($userId, $zonneSlotId);

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

  public function cancelReservation($reservationId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("DELETE FROM reservations where reservation_id = :reservationId");
    $query->bindParam(":reservationId", $reservationId, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return true;
    } else {
      return false;
    }
  }

  public function removeFromWaitingList($userId, $zonneSlotId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("DELETE FROM waitinglist where user_id = :userId and zoneSlot_id = :zonneSlotId");
    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
    $query->bindParam(":zonneSlotId", $zonneSlotId, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return true;
    } else {
      return false;
    }
  }
  public function addToWaitingList($zoneSlot_id, $userId)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("INSERT INTO waitingList VALUES (:userId,:zonneSlotId,DEFAULT)");
    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
    $query->bindParam(":zonneSlotId", $zoneSlot_id, PDO::PARAM_STR, 255);


    if ($query->execute()) {
      return ['msg' => "You are added to the waiting list for this zone! Once there is a free place we will notify you."];
    } else {
      return ['msg' => $query->errorInfo()[2]];
    }
  }

  public function getWaitingList($userId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select z.zone_id, f.fair_id, zs.zoneslot_id, zs.free_slots, zs.start_date, f.title as fair_title,z.title as zone_tile,zs.opening_slot,zs.closing_slot
    from waitinglist, zones z,zoneslots zs,fair f where z.fair_id = f.fair_id and zs.zone_id = z.zone_id and zs.zoneslot_id in (select zoneslot_id from waitinglist where waitinglist.user_id = :userId );");

    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);

    $waitingList = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $waitingListInfo = [
            "zone_id" => $row['zone_id'],
            "fair_id" => $row['fair_id'],
            "zoneslot_id" => $row['zoneslot_id'],
            "free_slots" => $row['free_slots'],
            "date" => $row['start_date'],
            "fairTitle" => $row['fair_title'],
            "zoneTitle" => $row['zone_tile'],
            "opening_slot" => $row['opening_slot'],
            "closing_slot" => $row['closing_slot'],
          ];
          array_push($waitingList, $waitingListInfo);
        }
        return $waitingList;
      }
    } else {
      return $query->errorInfo()[2];
    }
  }

  public function getReservations($userId)
  {
    //connect to database
    $conn = Database::connect();


    $query = $conn->prepare("select r.zoneslot_id, r.reservation_id, r.nOfPeople,zs.start_date,f.fair_id,z.zone_id, f.title as fair_title,z.title as zone_tile,zs.opening_slot,zs.closing_slot from zones z,reservations r,zoneslots zs,fair f where r.fair_id = f.fair_id and r.zoneslot_id = zs.zoneslot_id and zs.zone_id = z.zone_id and r.user_id = :userId");

    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);

    $resverationList = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $reservationInfo = [
            "reservation_id" => $row['reservation_id'],
            "nOfPeople" => $row['nofpeople'],
            "fair_id" => $row['fair_id'],
            "zoneslot_id" => $row['zoneslot_id'],
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
