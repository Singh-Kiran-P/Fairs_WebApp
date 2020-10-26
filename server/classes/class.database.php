<?php
class Database
{
  protected $conn;
  public function __construct()
  {
    // Connect to the Mysql database
    try {
      // get the env variables
      require __DIR__.'/../config/config.php';

      $this->conn = new PDO("$db_type:host=localhost;port=5432;dbname=$db_name", $db_user, $db_password);
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
