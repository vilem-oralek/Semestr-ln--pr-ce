<?php
// Deklarace php proměnných
$servername = "localhost";
$username = "zlatnjir";
$password = "webove aplikace";
$database = "zlatnjir";


//MYSQLI - MYSQL Improved
$conn = new mysqli($servername, $username, $password, $database);

//Ověření spojení
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/* // funkce získání connection
function getDbConnection() {
  $host = '127.0.0.1:3306';
  $db = '';
  $user = '';
  $pass = '';

  $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
  try {
      $pdo = new PDO($dsn, $user, $pass);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $pdo;
  } catch (PDOException $e) {
      echo 'Connection failed: ' . $e->getMessage();
      exit;
  }
} */
/*
else{
    echo "databáze připojená";
}

// -----TEST VÝPIS Z DATABÁZE-----

$sql = "SELECT * FROM Hotel";

$result = $conn->query($sql);

// Check if the query was successful
if ($result->num_rows > 0) {
  // Loop through each row of the result set
  while($row = $result->fetch_assoc()) {
    // Print each column value
    foreach ($row as $key => $value) {
        echo "$key: $value \n";
    }
    echo "\n"; // Add a new line after each row
  }
} else {
  echo "No records found.";
}
*/
?>