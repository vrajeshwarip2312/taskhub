<?php
// actions/create_notification.php
// This file can be included or called. it defines a function.

include "../config/db.php";
session_start();

/**
 * Create a notification.
 * Usage: include this file and call create_notification($conn, $user_id, $message);
 */
function create_notification($conn, $user_id, $message) {
    $stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $message);
    $stmt->execute();
}

// If called directly via POST (optional)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['message'])) {
    create_notification($conn, (int)$_POST['user_id'], trim($_POST['message']));
    echo json_encode(['success' => true]);
}
?>
