<?php
require_once "conn.php";

if (!$conn) {
    die("❌ Nepodařilo se připojit k databázi.");
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"] . " | ";
        echo "Jméno: " . $row["jmeno"] . " | ";
        echo "Email: " . $row["email"] . "<br>";
    }
} else {
    echo "Žádné záznamy nenalezeny.";
}
?>