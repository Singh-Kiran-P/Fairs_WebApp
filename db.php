<?php

try {

  $dbh = new PDO("pgsql:host=localhost;port=5432;dbname=kiransingh;user=postgres;password=singh");
  foreach ($dbh->query('SELECT * from accounts') as $row) {
    print_r($row['username']);
    echo "<br>";
  }
  $dbh = null;
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
