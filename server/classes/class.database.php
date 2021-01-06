<?php
class Database
{
  public static function connect()
  {
    // Connect to the Postgrs database
    try {
      // get the env variables
      require __DIR__ . '/../config/config.php';

      $conn = new PDO("$db_type:host=localhost;port=5432;dbname=$db_name", $db_user, $db_password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
      return $conn;
    } catch (PDOException $e) {
      die("Error!: " . $e->getMessage() . "\n");
    }
  }
}
