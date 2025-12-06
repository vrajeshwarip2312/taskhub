<?php
// actions/delete_comment.php
include "../config/auth_check.php";
include "../config/db.php";

header('Content-Type: application/json');

$comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
if ($comment_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid id']);
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';

// Allow delete if commenter or admin
if ($role === 'admin') {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
} else {
    $stmt = $conn->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
