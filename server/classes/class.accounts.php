<?php

/**
 * sources:
 *    https://www.sitepoint.com/community/t/admin-check-login-just-redirects-back-to-login-page/114002/3
 *    https://alexwebdevelop.com/php-password-hashing/
 *    https://alexwebdevelop.com/user-authentication/
 */

include_once "class.database.php";
include_once 'class.mail.php';
include_once __DIR__ . "/../preventions/func.xss.php";

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
    if (strlen($email_username) > 49)
      return  ["msg" => "Email/Username length to big! Max size 50", "vaild" => false];

    if ($email_username == "")
      return  ["msg" => "Email/Username cannot be empty!", "vaild" => false];


    if (strlen($password) > 49)
      return  ["msg" => "Password length to big! Max size 50", "vaild" => false];

    if ($password == "")
      return  ["msg" => "Password cannot be empty!", "vaild" => false];




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
        if ($row['active'] == false)
          return  ["msg" => "Please check your email for activation!", "vaild" => false];

        if (password_verify($password, $row['password'])) {
          /* Call the login helper */
          $redirectTo = $this->_login($row);
          return ["redirectTo" => $redirectTo, "vaild" => true];
        }
      }
    }

    return  ["msg" => "Incorrect username or password!", "vaild" => false];
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
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/visitor/profile.php';
    } else if ($row['type'] === "admin") {
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/admin/actions.php';
    }

    return $redirectTo;
  }

  public function checkIfEmailInDatabase($email)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account only using email
    $queryUser = $conn->prepare("select * from accounts where email=:email");
    $queryUser->bindParam(":email", $email, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      if ($queryUser->rowCount() > 0) { //token is valid with this $email
        return true;
      }
    }
    return false;
  }

  /**
   * Send mail to reset password
   *
   * @param [type] $email
   * @return void
   */
  public function sendResetPasswordMail($email)
  {
    require __DIR__ . '/../config/config.php';

    $mail = new Mail();
    $token = md5(uniqid($email, true));
    if ($this->_addResetPasswordTokenToDatabase($token, $email)) {

      $link = $rootURL . "/~kiransingh/project/server/dashboard/auth/resetPassword.php?token=" . $token . "&email=" . $email;
      $body =
        "<body>
      Hi,
      Someone (hopefully you!) requested a password reset for your account. Click the link below to choose a new password.
      <br>
      <a href='" .
        $link
        . "'>Reset Password</a>
        <br><br>
        If the link above not works then copy past this link<br>
        " .
        $link
        . "
        <br><br>
      If you did not request a password reset, you can simply ignore this message.
      Regards,
      Singh Kiran
      </body>

    ";
      $mail->sendEmail($email, "Request for password recovery", $body);
      return true;
    } else
      return false;
  }

  private function _addResetPasswordTokenToDatabase($token, $email)
  {

    //connect to database
    $conn = Database::connect();
    $query = $conn->prepare("INSERT INTO password_reset VALUES (DEFAULT, :email,:token)");

    // Bind params
    $query->bindParam(":token", $token, PDO::PARAM_STR, 255);
    $query->bindParam(":email", $email, PDO::PARAM_STR, 255);

    if ($query->execute())
      return true;
    else
      return false;
  }

  /**
   * Reset password
   *
   * @param [type] $email
   * @param [type] $token
   * @return void
   */
  public function resetPassword($email, $token, $password)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account only using email
    $queryUser = $conn->prepare("select * from password_reset where token=:token and email=:email");
    $queryUser->bindParam(":token", $token, PDO::PARAM_STR, 255);
    $queryUser->bindParam(":email", $email, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      if ($queryUser->rowCount() > 0) { //token is valid with this $email
        return $this->_changePassword($password, $email);
      }
    }
  }

  private function _changePassword($password, $email)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account only using email
    $queryUser = $conn->prepare("update accounts set password=:pass where email=:email");
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $queryUser->bindParam(":pass", $hashedPassword, PDO::PARAM_STR, 255);
    $queryUser->bindParam(":email", $email, PDO::PARAM_STR, 255);

    if ($queryUser->execute())
      return true;
    else
      return false;
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

    //check input
    if (strlen($email) > 49)
      return ['msg' => "Email length to  big!", 'val' => false];
    if ($email == "")
      return ['msg' => "Email cannot be empty!", 'val' => false];


    if (strlen($name) > 49)
      return ['msg' => "Name length to  big!", 'val' => false];
    if ($name == "")
      return ['msg' => "Name cannot be empty!", 'val' => false];


    if (strlen($password) > 49)
      return ['msg' => "Password length to  big!", 'val' => false];
    if ($password == "")
      return ['msg' => "Password cannot be empty!", 'val' => false];


    if (strlen($username) > 49)
      return ['msg' => "Username length to  big!", 'val' => false];
    if ($email == "")
      return ['msg' => "Username cannot be empty!", 'val' => false];

    if ($type == "")
      return ['msg' => "Type cannot be empty!", 'val' => false];


    if (!($type == "city" || $type == "visitor"))
      return ['msg' => "Invaild type", 'val' => false];


    // Validating EMail and username
    if ($this->checkIfEmail($email))
      return ['msg' => "Email already taken!", 'val' => false];
    else if ($this->checkIfUsername($username))
      return ['msg' => "Username already taken!", 'val' => false];
    else {
      $query = $conn->prepare("INSERT INTO accounts VALUES (DEFAULT, :name,:username,:password,:email,:type,NOW(),DEFAULT,:activationHash)");

      // Bind params
      $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
      $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
      $query->bindParam(":type", $type, PDO::PARAM_STR, 50);
      $query->bindParam(":name", $name, PDO::PARAM_STR, 50);

      $activationHash = password_hash($email, PASSWORD_DEFAULT);
      $query->bindParam(":activationHash", $activationHash, PDO::PARAM_STR, 255);

      /* Secure password hash. */
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $query->bindParam(":password", $hashedPassword, PDO::PARAM_STR, 255);

      if ($query->execute()) {
        //send verification
        $this->sendConfirmationMail($email, $activationHash);
        // Login the registered user, to get the identity and making session variables
        // $this->login($email, $password);
        return ['msg' => "We have send a verification email, please check your email.", 'val' => true, 'userId' => $conn->lastInsertId()];
      } else {
        return ['msg' => $conn->errorInfo()[2], 'val' => false];
      }
    }

    return  ['msg' => "Failed to register, please try again later.", 'val' => false];
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

    //check input
    if (strlen($telephone) > 49)
      return "Telephone number length to  big!";

    if ($telephone == "")
      return "Telephone number cannot be empty!";


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
   * Send confimation email with activationHash to user email address
   *
   * @param [type] $email
   * @param [type] $userId
   * @return void
   */
  private function sendConfirmationMail($email, $activationHash)
  {
    require __DIR__ . '/../config/config.php';
    $mail = new Mail();

    $link =   $rootURL . "/~kiransingh/project/server/dashboard/auth/activate.php?activationHash=" . $activationHash;
    $body =
      "<body>
    Hello,

    Thank you for registering.

    Please click on the link below to activate your account.<br>
    <a href='" .
      $link
      . "'>Activate account</a>
      <br><br>

    If the link above not works then copy past this link<br>
    " .
      $link
      . "
      <br><br>
    Best regards,
    Singh Kiran

    </body>";
    $mail->sendEmail($email, "Activate account Fairs Of Belgium", $body);
  }
  /**
   * Checks if activation hash is valid
   *
   * @param string $activationHash
   * @return int user_id
   */
  public function _checkIfActivationHashIsValid($activationHash)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account only using email
    $queryUser = $conn->prepare("select * from accounts where activation_hash=:activationHash");
    $queryUser->bindParam(":activationHash", $activationHash, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {

      if ($queryUser->rowCount() > 0) {
        $row = $queryUser->fetch(PDO::FETCH_ASSOC);
        return $row['user_id'];
      } else
        return null;
    }
  }

  public function activateAccount($user_id)
  {
    //connect to database
    $conn = Database::connect();

    // check if there is account only using email
    $queryUser = $conn->prepare("Update accounts set active=true where user_id=:id");
    $queryUser->bindParam(":id", $user_id, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      return true;
    } else {
      return false;
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
