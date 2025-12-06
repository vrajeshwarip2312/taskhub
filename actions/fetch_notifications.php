<?php
// actions/fetch_notifications.php
include "../config/auth_check.php";
require_once __DIR__ . '/../config/db.php';


header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

// Return last 20 notifications
$stmt = $conn->prepare("SELECT id, message, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
$out = [];
while ($r = $res->fetch_assoc()) $out[] = $r;

echo json_encode($out);
