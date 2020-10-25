<?php
class Database
{
  protected $conn;
  public function __construct()
  {
    // Connect to the Mysql database
    try {
      // get the env variables
      include $_SERVER['DOCUMENT_ROOT'] . '/~kiransingh/project/server/config/config.php';

      $this->conn = new PDO("$db_type:host=$db_host;port=$port;dbname=$db_name", $db_user, $db_password);
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
