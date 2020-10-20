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
        require '../config/config.php';

        // check if there is account
        $queryUser = $this->conn->prepare("select * from users where email=:email and password=:password");
        $queryUser->bindParam(":email", $email, PDO::PARAM_STR, 255);
        $queryUser->bindParam(":password", $password, PDO::PARAM_STR, 255);

        if ($queryUser->execute()) {
            if ($queryUser->rowCount() > 0) { // user exits in db

                $res = $queryUser->fetch();

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
                } else {
                    $redirectTo = $rootURL . '/~kiransingh/project/server/dashboard/user.php';
                }

                // Json obj to send back
                $data = ['redirectTo' => $redirectTo,
                    'status' => 200];
                echo json_encode($data);

            } else {
                // Json obj to send back
                $data = ['msg' => 'User does not exites!', 'status' => 404];
                echo json_encode($data);
            }
        }
    }

    public function register($email, $password, $username, $type)
    {
        if ($this->checkIfUserExits($username, $email)) { // create new user

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $query = $this->conn->prepare("INSERT INTO users (`id`, `type`, `username`, `password`, `email`) VALUE (null, :type,:username,:password,:email)");
            $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
            $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
            $query->bindParam(":password", $password, PDO::PARAM_STR, 255);
            $query->bindParam(":type", $type, PDO::PARAM_STR, 50);

            if ($query->execute()) {
                // set member variables
                $_SESSION['username'] = $username;


                // Json obj to send back
                $data = ['msg' => "Account created successfully. Go ahead at login",
                    'status' => 200];
                echo json_encode($data);

            } else { // Json obj to send back
                $data = ['msg' => $this->conn->errorInfo(),
                    'status' => 404];
                echo json_encode($data);
            }
        }
    }

    private function checkIfUserExits($username, $email)
    {

        $queryUser = $this->conn->prepare("select * from users where username=:username");
        $queryUser->bindParam(":username", $username, PDO::PARAM_STR, 255);

        if ($queryUser->execute()) {
            if ($queryUser->rowCount() > 0) {
                // Json obj to send back
                $data = ['msg' => "Username is already taken!"];
                echo json_encode($data);
                return false;
            }
        } else {
            // Json obj to send back
            $data = ['msg' => $this->conn->errorInfo()];
            header('Content-Type: application/json');
            echo json_encode($data);
        }

        $queryEmail = $this->conn->prepare("select * from users where email=:email");
        $queryEmail->bindParam(":email", $email, PDO::PARAM_STR, 255);

        if ($queryEmail->execute()) {
            if ($queryEmail->rowCount() > 0) {
                // Json obj to send back
                $data = ['msg' => "Email is already taken!"];
                echo json_encode($data);
                return false;
            }
        } else {
            // Json obj to send back
            $data = ['msg' => $this->conn->errorInfo()];
            echo json_encode($data);
        }

        return true;
    }
}
