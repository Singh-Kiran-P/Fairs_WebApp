<?php
// get the env variables
require '../config/db.php';

$request = json_decode(file_get_contents('php://input'), true);

// If the variables are set create new user in database
if (count($request) != 0) {
    $username = $request['username'];
    $password = $request['password'];
    $email = $request['email'];

    // Connect to the Mysql database
    try
    {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "\n");
    }

    if (checkIfUserExits($username, $email)) { // create new user
        $query = $conn->prepare("insert into users value (null,:username,:password,:email)");
        $query->bindParam(":username", $username, PDO::PARAM_STR, 255);
        $query->bindParam(":email", $email, PDO::PARAM_STR, 255);
        $query->bindParam(":password", $password, PDO::PARAM_STR, 255);

        if ($query->execute()) {
            // Json obj to send back
            $data = ['msg' => "Account created successfully"];
            header('Content-Type: application/json');
            echo json_encode($data);

        } else { // Json obj to send back
            $data = ['msg' => $conn->errorInfo()];
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }

    $conn = null;
}

function checkIfUserExits($username, $email)
{
    require '../config/db.php';

    try
    {
        $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
    } catch (PDOException $e) {
        die("Error!: " . $e->getMessage() . "\n");
    }

    $queryUser = $conn->prepare("select * from users where username=:username");
    $queryUser->bindParam(":username", $username, PDO::PARAM_STR, 255);

    if ($queryUser->execute()) {
        if ($queryUser->rowCount() > 0) {
            // Json obj to send back
            $data = ['msg' => "Username is already taken!"];
            header('Content-Type: application/json');
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
            header('Content-Type: application/json');
            echo json_encode($data);
            return false;
        }
    } else {
        // Json obj to send back
        $data = ['msg' => $conn->errorInfo()];
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    return true;
}
