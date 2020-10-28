<?php

/**
 * sources:
 *    https://www.sitepoint.com/community/t/admin-check-login-just-redirects-back-to-login-page/114002/3
 *    https://alexwebdevelop.com/php-password-hashing/
 *    https://alexwebdevelop.com/user-authentication/
 */

include "class.database.php";

/* Accounts class holds the users identity and fuction/methode that are related to users*/
class Accounts extends Database
{

  private $userId;
  private $name;
  private $username;
  private $type;
  private $email;
  private $created_on;
  private $last_login;

  /**
   * Accounts constuctor to init member variables
   *
   * @param [type] $userId
   */
  public function init($userId)
  {
    // check if there is account with this userID
    $queryUser = $this->conn->prepare("select * from accounts where user_id=:userid");
    $queryUser->bindParam(":userid", $userId, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      // if query result is not empty -> user does exits in DB

      if ($queryUser->rowCount() > 0) {
        $row = $queryUser->fetch(PDO::FETCH_ASSOC);
        // set member variables
        $this->userId = $row['user_id'];
        $this->type = $row['type'];
        $this->username = $row['username'];
        $this->email = $row['email'];
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
    // check if there is account only using email
    $queryUser = $this->conn->prepare("select * from accounts where email=:email or username=:username");
    $queryUser->bindParam(":email", $email_username, PDO::PARAM_STR, 255);
    $queryUser->bindParam(":username", $email_username, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      // if query result is not empty -> user does exits in DB

      if ($queryUser->rowCount() > 0) {
        $row = $queryUser->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $row['password'])) {
          /* Call the login helper */
          $this->_login($row);
          return true;
        } else {
          return false;
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
    $_SESSION['name'] = $row['name'];
    $_SESSION['type'] = $row['type'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['email'] = $row['email'];

    // set member variables
    $this->userId = $row['user_id'];
    $this->type = $row['type'];
    $this->username = $row['username'];
    $this->email = $row['email'];


    // set redirect path
    // 3 types of users [city,visitor,admin]
    $redirectTo = "";

    if ($row['type'] === "city") {
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/city/profile.php';
    } else if ($row['type'] === "visitor") {
      $redirectTo = $rootURL . '/~kiransingh/project/static/dashboard/visitor/main.php';
    }

    // add the redirect path to session variable
    $_SESSION['redirectTo'] = $redirectTo;
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
    // Validating EMail and username
    if ($this->checkIfEmail($email))
      return ['msg' => "Email already taken!", 'val' => false];
    else if ($this->checkIfUsername($username))
      return ['msg' => "Username already taken!", 'val' => false];
    else {
      $query = $this->conn->prepare("INSERT INTO accounts VALUES (DEFAULT, :name,:username,:password,:email,:type,NOW(),NULL)");

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
        return ($this->conn->errorInfo());
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
    $query = $this->conn->prepare("INSERT INTO city (city_id,user_id,telephone,short_description) VALUES (DEFAULT,:userId,:telephone,:desc)");
    $query->bindParam(":userId", $this->userId, PDO::PARAM_STR, 255);
    $query->bindParam(":telephone", $telephone, PDO::PARAM_STR, 255);
    $query->bindParam(":desc", $desc, PDO::PARAM_STR, 255);

    if ($query->execute()) {
      return true;
    } else {
      return true;
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

    $queryEmail = $this->conn->prepare("select * from accounts where email=:email");
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
    $queryUser = $this->conn->prepare("select * from accounts where username=:username");
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
}
