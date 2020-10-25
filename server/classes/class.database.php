<?php
class Database
{
  protected $conn;
  public function __construct()
  {
    // Connect to the Mysql database
    try {
      // get the env variables
      include '../../server/config/config.php';

      $this->conn = new PDO("pgsql:host=localhost;port=5432;dbname=kiransingh", 'postgres', 'singh');
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
