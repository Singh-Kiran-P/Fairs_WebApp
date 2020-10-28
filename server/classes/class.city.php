<?php
include "class.database.php";

/* City class holds the City identity and fuction/methode that are related to users*/
class City extends Database
{
  private $short_desc;
  private $telephone;

  /**
   * Constructor with the UserId will set the member var of this class
   *
   * @param [int] $userId
   */
  public function init($userId)
  {
    // check if there is account with this userID
    $queryUser = $this->conn->prepare("select * from city where user_id=:userid");
    $queryUser->bindParam(":userid", $userId, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      // if query result is not empty -> user does exits in DB
      if ($queryUser->rowCount() > 0) {
        $row = $queryUser->fetch(PDO::FETCH_ASSOC);
        // set member variables
        $this->short_desc = $row['short_description'];
        $this->telephone = $row['telephone'];
      } else {
        $s = $queryUser->errorInfo()[2];
        throw new Exception("UserId error", 1);
      }
    }
  }

  public function getTelephone()
  {
    return $this->telephone;
  }

  public function setTelephone($telephone)
  {
    $this->telephone = $telephone;
  }

  public function getShort_desc()
  {
    return $this->short_desc;
  }

  public function setShort_desc($short_desc)
  {
    $this->short_desc = $short_desc;
  }
}
