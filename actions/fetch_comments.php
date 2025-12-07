<?php
// actions/fetch_comments.php
session_start();
require_once __DIR__ . '/../config/db.php';

$task_id = isset($_GET['task_id']) ? intval($_GET['task_id']) : 0;
if ($task_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT c.id, c.comment, c.created_at, c.user_id, u.name AS user_name
    FROM comments c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.task_id = ?
    ORDER BY c.created_at ASC
");
$stmt->bind_param('i', $task_id);
$stmt->execute();
$res = $stmt->get_result();

$rows = [];
while ($r = $res->fetch_assoc()) $rows[] = $r;

header('Content-Type: application/json');
echo json_encode($rows);
