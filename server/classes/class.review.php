<?php

include_once "class.database.php";
include_once __DIR__ . "/../preventions/func.xss.php";


/* Accounts class holds the users identity and fuction/methode that are related to users*/
class Review
{
  public function getInfoForReviewPage($reservationId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select f.title as fTitle,z.title as zTitle,z.zone_id
    from reservations r,zones z,fair f,zoneslots zs
    where r.zoneslot_id = zs.zoneslot_id and zs.zone_id = z.zone_id and z.fair_id = f.fair_id and r.reservation_id=:reservationId");
    $query->bindParam(":reservationId", $reservationId, PDO::PARAM_STR, 255);


    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        $row = $query->fetch();
        $info = array(
          "zoneId" => $row['zone_id'],
          "zoneTitle" => $row['ztitle'],
          "fairTitle" => $row['ftitle'],
        );
        return $info;
      }
    } else {
      return $query->errorInfo()[2];
    }

    return NULL;
  }

  public function writeReview($zoneId, $userId, $review, $rating)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("insert into review values (DEFAULT,:zoneId,:userId,:rating,:review)");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);
    $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
    $query->bindParam(":rating", $rating, PDO::PARAM_STR, 255);
    $query->bindParam(":review", $review, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return "Your Review has been sent! ";
    } else {
      return $query->errorInfo()[2];
    }
  }

  public function buildReviewHTML($reviewInfo)
  {
    $outHTML = '';
    foreach ($reviewInfo as $review) {
      $name = $review['name'];
      $reviewMsg = $review['review'];
      $rating = $review['rating'];

      $outHTML .= '<div class="reviewbox">';
      $outHTML .= '<div class="name">';
      $outHTML .= '<i class="fa fa-user fa-7x"></i>';
      $outHTML .= '<p>' . _e($name) . ':</p>';
      $outHTML .= '</div>';
      $outHTML .= '<div class="reviewMsg">' . _e($reviewMsg) . '</div>';
      $outHTML .= '<div class="rating">';

      $i = 0;
      while ($i < 5) {
        if ($i <= $rating)
          $outHTML .= '<span class="fa fa-star checked"></span>';
        else
          $outHTML .= '<span class="fa fa-star"></span>';

        $i++;
      }

      $outHTML .= '</div>';
      $outHTML .= '</div>';
    }

    return  $outHTML;
  }

  public function getReviewInfo($zoneId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select a.name, r.review, r.rating from review r,accounts a where r.zone_id= :zoneId and r.user_id = a.user_id");
    $query->bindParam(":zoneId", $zoneId, PDO::PARAM_STR, 255);

    $listReviews = array();

    if ($query->execute()) {
      if ($query->rowCount() > 0) { // There is atleast 1 row of timeslots
        while ($row = $query->fetch()) {
          $name = $row['name'];
          $review = $row['review'];
          $rating = $row['rating'];

          array_push($listReviews, ['name' => $name, 'review' => $review, 'rating' => $rating]);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $listReviews;
  }
}
