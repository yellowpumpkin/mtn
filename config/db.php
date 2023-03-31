
<?php
$servername = "localhost";
$username = "inwzacom_test";
$password = "!QAZxsw23edc";
$database = "inwzacom_Test";

try {
  $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
  
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
} catch(PDOException $e) {
 
}




?>