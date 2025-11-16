<?php
session_start();
include 'conn.php';

// Kontrola přihlášení
if (!isset($_SESSION['user_id'])) {
    echo "Uživatel není přihlášen.";
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT jmeno, prijmeni FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    echo "Jméno: " . htmlspecialchars($user['jmeno']) . "<br>";
    echo "Příjmení: " . htmlspecialchars($user['prijmeni']);
} else {
    echo "Uživatel nenalezen.";
}
?>