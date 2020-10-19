<?php
// https://www.sitepoint.com/community/t/admin-check-login-just-redirects-back-to-login-page/114002/3

include "class.database.php";
class Users extends Database
{

    public function login($email, $password)
    {

    }

    public function register($email, $password, $username, $type)
    {
        if (checkIfUserExits($username, $email, $this->conn)) { // create new user

            $query = $this->conn->prepare("INSERT INTO users (`id`, `type`, `username`, `password`, `email`) VALUE (null, :type,:username,:password,:email)");
            $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
            $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
            $query->bindParam(":password", $password, PDO::PARAM_STR, 255);
            $query->bindParam(":type", $type, PDO::PARAM_STR, 50);

            if ($query->execute()) {
                // Json obj to send back
                $data = ['msg' => "Account created successfully. Go ahead at login"];
                echo json_encode($data);

            } else { // Json obj to send back
                $data = ['msg' => $this->conn->errorInfo()];
                echo json_encode($data);
            }
        }
        function checkIfUserExits($username, $email, $conn)
        {

            $queryUser = $conn->prepare("select * from users where username=:username");
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
                $data = ['msg' => $conn->errorInfo()];
                header('Content-Type: application/json');
                echo json_encode($data);
            }

            $queryEmail = $conn->prepare("select * from users where email=:email");
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
                $data = ['msg' => $conn->errorInfo()];
                echo json_encode($data);
            }

            return true;
        }

    }

}
