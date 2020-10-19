<?php

// get the env variables

class Database
{
    protected $conn;
    public function __construct()
    {
        // Connect to the Mysql database
        try
        {
            require '../config/config.php';
            $this->conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            return $this->conn;
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage() . "\n");
        }
    }

    public function __destruct()
    {
        $this->conn = null;
    }

}
