<?php 
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  error_reporting(E_ALL); 
  $host = "localhost";       
  $user = "root";            
  $password = "";            
  $dbname = "clothing_store"; 

  try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");
  } catch (mysqli_sql_exception $e) {
      die("<h3 style='color:red;'>Database connection failed: " . $e->getMessage() . "</h3>");
  }

?>