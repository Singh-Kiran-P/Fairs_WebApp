<?php

/**
 * sources:
 *    https://www.sitepoint.com/community/t/admin-check-login-just-redirects-back-to-login-page/114002/3
 *    https://alexwebdevelop.com/php-password-hashing/
 *    https://alexwebdevelop.com/user-authentication/
 */

include_once "class.database.php";

/* Accounts class holds the users identity and fuction/methode that are related to users*/
class Accounts
{

  private $userId;
  private $cityId;
  private $name = "";
  private $username = "";
  private $type = "";
  private $email = "";
  private $created_on = "";
  private $last_login = "";
  private $short_desc = "";
  private $telephone = "";

  /**
   * Accounts constuctor to init member variables
   *
   * @param [type] $userId
   */
  public function init($userId, $type)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account with this userID
    if ($type == "city")
      $queryUser = $conn->prepare("select * from accounts a,city c where a.user_id=:userid and c.user_id= a.user_id");
    if ($type == "visitor")
      $queryUser = $conn->prepare("select * from accounts a where a.user_id=:userid");

    $queryUser->bindParam(":userid", $userId, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      // if query result is not empty -> user does exits in DB

      if ($queryUser->rowCount() > 0) {
        $row = $queryUser->fetch(PDO::FETCH_ASSOC);

        // set member variables
        $this->userId = intval($row['user_id']);
        $this->type = $row['type'];
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->name = $row['name'];

        if ($type == "city") {

          $this->cityId = intval($row['city_id']);
          $_SESSION['cityId'] = $this->cityId;

          $this->short_desc = $row['short_description'];
          $this->telephone = $row['telephone'];
        }
      } else {
        throw new Exception("UserId error", 1);
      }
    }
  }

  /**
   * Login
   *
   * @param [string] $email
   * @param [string] $password
   * @return bool Login status: false = not authenticated, true = authenticated.
   */
  public function login($email_username, $password)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account only using email
    $queryUser = $conn->prepare("select * from accounts where email=:email or username=:username");
    $queryUser->bindParam(":email", $email_username, PDO::PARAM_STR, 255);
    $queryUser->bindParam(":username", $email_username, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      // if query result is not empty -> user does exits in DB

      if ($queryUser->rowCount() > 0) {
        $row = $queryUser->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password'])) {
          /* Call the login helper */
          $redirectTo = $this->_login($row);
          return $redirectTo;
        } else {
          return "";
        }
      }
    }
  }

  /**
   * Login helper function to set the session variable and redirectPath
   *
   * @return void
   */
  public function _login($row)
  {
    include __DIR__ . '/../config/config.php';

    // set session variables
    $_SESSION['loggedin'] = true;
    $_SESSION['userId'] = $row['user_id'];
    /*     $_SESSION['username'] = $row['username'];*/
    $_SESSION['type'] = $row['type'];

    // set redirect path
    // 3 types of users [city,visitor,admin]
    $redirectTo = "";

    if ($row['type'] === "city") {
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/city/profile.php';
    } else if ($row['type'] === "visitor") {
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/visitor/searchFairs.php';
    } else if ($row['type'] === "admin") {
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/admin/actions.php';
    }

    return $redirectTo;
  }

  /**
   * Register new users
   *
   * @param [string] $email
   * @param [string] $password
   * @param [string] $username
   * @param [string] $type
   * @return string It returns a message that can be showed at the front-end
   */
  public function register($name, $email, $password, $username, $type)
  {
    //connect to database
    $conn = Database::connect();

    // Validating EMail and username
    if ($this->checkIfEmail($email))
      return ['msg' => "Email already taken!", 'val' => false];
    else if ($this->checkIfUsername($username))
      return ['msg' => "Username already taken!", 'val' => false];
    else {
      $query = $conn->prepare("INSERT INTO accounts VALUES (DEFAULT, :name,:username,:password,:email,:type,NOW(),NULL)");

      // Bind params
      $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
      $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
      $query->bindParam(":type", $type, PDO::PARAM_STR, 50);
      $query->bindParam(":name", $name, PDO::PARAM_STR, 50);

      /* Secure password hash. */
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $query->bindParam(":password", $hashedPassword, PDO::PARAM_STR, 255);

      if ($query->execute()) {
        // Login the registered user, to get the identity and making session variables
        $this->login($email, $password);
        return ['msg' => "Username already taken!", 'val' => true];
      } else {
        return ($conn->errorInfo());
      }
      echo "<script>console.log('Debug Objects: " . "Error in Accounts->register" . "' );</script>";
    }
  }

  /**
   * CompleteRegisteration ask more question to a 'gemeente' to get all the important information
   *
   * @param [string] $telephone
   * @param [string] $desc
   * @return bool true if al went good else false
   */
  public function completeRegisteration($telephone, $desc)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("INSERT INTO city (city_id,user_id,telephone,short_description) VALUES (DEFAULT,:userId,:telephone,:desc)");
    $query->bindParam(":userId", $this->userId, PDO::PARAM_STR, 255);
    $query->bindParam(":telephone", $telephone, PDO::PARAM_STR, 255);
    $query->bindParam(":desc", $desc, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return "true";
    } else {
      return $query->errorInfo()[2];
    }
  }

  /**
   * Checking email is taken or not
   *
   * @param [string] $email
   * @return bool
   */
  private function checkIfEmail($email)
  {
    //connect to database
    $conn = Database::connect();

    $queryEmail = $conn->prepare("select * from accounts where email=:email");
    $queryEmail->bindParam(":email", $email, PDO::PARAM_STR, 255);

    if ($queryEmail->execute()) {
      if ($queryEmail->rowCount() > 0) {

        return true;
      }
    } else {
      return true;
    }

    return false;
  }

  /**
   * checking if username is taken or not
   *
   * @param [string] $username
   * @return bool
   */
  private function checkIfUsername($username)
  {
    //connect to database
    $conn = Database::connect();

    $queryUser = $conn->prepare("select * from accounts where username=:username");
    $queryUser->bindParam(":username", $username, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      if ($queryUser->rowCount() > 0) {
        return true;
      }
    } else {
      return true;
    }
    return false;
  }

  public function getAllVisitors()
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from accounts where type = 'visitor'");

    $listOfVisitors = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {
          $visitor = array(
            'userId' => $row['user_id'],
            'name' => $row['name']
          );

          array_push($listOfVisitors, $visitor);
        }
      }
      return $listOfVisitors;
    } else {
      return $query->errorInfo()[2];
    }
    return NULL;
  }

  public function getNotifications($userId)
  {
    //connect to database
    $conn = Database::connect();

    $query = $conn->prepare("select * from notifications where user_id=:id");
    $query->bindParam(":id", $userId, PDO::PARAM_STR, 255);

    $list = array();
    if ($query->execute()) {
      if ($query->rowCount() > 0) {
        while ($row = $query->fetch()) {

          $msg = [
            'notification_id' => $row['notification_id'],
            'msg' => $row['msg']
          ];

          array_push($list, $msg);
        }
      }
    } else {
      return $query->errorInfo()[2];
    }

    return $list;
  }
  public function deleteNotification($notification_id, $userId, $all)
  {
    //connect to database
    $conn = Database::connect();

    try {
      if ($all) {
        $query = $conn->prepare("DELETE FROM notifications WHERE  user_id=:userId ");
        $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
      } else {
        $query = $conn->prepare("DELETE FROM notifications WHERE notification_id = :id and  user_id=:userId ");
        $query->bindParam(":id", $notification_id, PDO::PARAM_STR, 255);
        $query->bindParam(":userId", $userId, PDO::PARAM_STR, 255);
      }

      $query->execute();
    } catch (\Throwable $th) {
      //throw $th;
    }
  }

  /* ___________________________ GETTER / SETTERS_________________________________ */

  public function getUserId()
  {
    return $this->userId;
  }


  public function setUserId($userId)
  {
    $this->userId = $userId;

    return $this;
  }


  public function getName()
  {
    return $this->name;
  }


  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  public function getUsername()
  {
    return $this->username;
  }


  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }


  public function getType()
  {
    return $this->type;
  }


  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }


  public function getEmail()
  {
    return $this->email;
  }


  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  public function getCreated_on()
  {
    return $this->created_on;
  }

  public function setCreated_on($created_on)
  {
    $this->created_on = $created_on;

    return $this;
  }


  public function getLast_login()
  {
    return $this->last_login;
  }


  public function setLast_login($last_login)
  {
    $this->last_login = $last_login;

    return $this;
  }

  public function getShort_desc()
  {
    return $this->short_desc;
  }

  public function setShort_desc($short_desc)
  {
    $this->short_desc = $short_desc;

    return $this;
  }

  public function getTelephone()
  {
    return $this->telephone;
  }


  public function setTelephone($telephone)
  {
    $this->telephone = $telephone;

    return $this;
  }
}
