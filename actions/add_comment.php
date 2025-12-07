<?php
// actions/add_comment.php
session_start();
require_once __DIR__ . '/../config/db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status'=>'error','msg'=>'invalid_method']);
    exit;
}

// Check user session
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) {
    http_response_code(401);
    echo json_encode(['status'=>'error','msg'=>'not_authenticated']);
    exit;
}

// Validate inputs
$task_id = isset($_POST['task_id']) ? intval($_POST['task_id']) : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($task_id <= 0 || $comment === '') {
    http_response_code(400);
    echo json_encode(['status'=>'error','msg'=>'missing_fields']);
    exit;
}

// Insert comment (prepared stmt)
$stmt = $conn->prepare("INSERT INTO comments (task_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status'=>'error','msg'=>'db_prepare_failed']);
    exit;
}
$stmt->bind_param('iis', $task_id, $user_id, $comment);
$ok = $stmt->execute();

if ($ok) {
    echo json_encode(['status'=>'ok','msg'=>'success']);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','msg'=>'db_error']);
}
