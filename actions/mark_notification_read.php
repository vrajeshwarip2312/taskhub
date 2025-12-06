<?php
// actions/mark_notification_read.php
include "../config/auth_check.php";
include "../config/db.php";
header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid ID']);
    exit;
}

$stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();

// success even if already read
echo json_encode(['success' => true]);
?>
