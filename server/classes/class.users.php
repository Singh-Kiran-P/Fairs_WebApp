<?php
// https://www.sitepoint.com/community/t/admin-check-login-just-redirects-back-to-login-page/114002/3

include "class.database.php";

class Users extends Database
{
  private $userId;
  private $type;
  private $username;
  private $email;

  public function login($email, $password)
  {
    include $_SERVER['DOCUMENT_ROOT'] . '/~kiransingh/project/server/config/config.php';

    // check if there is account
    $queryUser = $this->conn->prepare("select * from users where email=:email and password=:password");
    $queryUser->bindParam(":email", $email, PDO::PARAM_STR, 255);
    $queryUser->bindParam(":password", $password, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
      if ($queryUser->rowCount() > 0) { // user exits in db

        $res = $queryUser->fetch();
        $redirectTo = "";

        // set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['userId'] = $res['id'];
        $_SESSION['type'] = $res['type'];
        $_SESSION['username'] = $res['username'];
        $_SESSION['email'] = $res['email'];

        // set member variables
        $this->userId = $res['id'];
        $this->type = $res['type'];
        $this->username = $res['username'];
        $this->email = $res['email'];

        if ($res['type'] === "gemeente") {
          $redirectTo = $rootURL . '/~kiransingh/project/server/dashboard/gemeente.php';
        } else if ($res['type'] === "bezoeker") {
          $redirectTo = $rootURL . '/~kiransingh/project/server/dashboard/user.php';
        }

        $_SESSION['redirectTo'] = $redirectTo;
      }
    }
  }

  public function register($email, $password, $username, $type)
  {

    if ($this->checkIfEmail($email)) {
      return "Email already taken!";
    } else if ($this->checkIfUsername($username)) {
      return "username already taken!";
    } else {
      $query = $this->conn->prepare("INSERT INTO users (`id`, `type`, `username`, `password`, `email`) VALUE (null, :type,:username,:password,:email)");
      $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
      $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
      $query->bindParam(":password", $password, PDO::PARAM_STR, 255);
      $query->bindParam(":type", $type, PDO::PARAM_STR, 50);

      if ($query->execute()) {
        $this->login($email,$password);// login the curr user in to set the session var
        return ("true");
      } else {
        return ($this->conn->errorInfo());
      }
    }

  }

  public function completeRegisteration($userId)
  {
    $query = $this->conn->prepare("INSERT INTO users (`id`, `type`, `username`, `password`, `email`) VALUE (null, :type,:username,:password,:email)");
    $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
    $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
    $query->bindParam(":password", $password, PDO::PARAM_STR, 255);
    $query->bindParam(":type", $type, PDO::PARAM_STR, 50);

    if ($query->execute()) {
      $this->login($email,$password);// login the curr user in to set the session var
      return ("true");
    } else {
      return ($this->conn->errorInfo());
    }
  }

  private function checkIfEmail($email)
  {

    $queryEmail = $this->conn->prepare("select * from users where email=:email");
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
  private function checkIfUsername($username)
  {
    $queryUser = $this->conn->prepare("select * from users where username=:username");
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
