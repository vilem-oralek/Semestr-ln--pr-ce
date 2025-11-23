<?php
session_start();
header('Content-Type: application/json');

error_log("=== SESSION CHECK ===");
error_log("Session ID: " . session_id());
error_log("User ID: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("All session data: " . print_r($_SESSION, true));

if (isset($_SESSION['user_id'])) {
    echo json_encode(['loggedIn' => true]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>