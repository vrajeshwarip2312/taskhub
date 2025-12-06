<?php
// actions/fetch_comments.php
include "../config/auth_check.php";
include "../config/db.php";

header('Content-Type: application/json');

$task_id = isset($_GET['task_id']) ? (int)$_GET['task_id'] : 0;
if ($task_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("
    SELECT c.id, c.comment, c.created_at, u.id AS user_id, u.name
    FROM comments c
    JOIN users u ON u.id = c.user_id
    WHERE c.task_id = ?
    ORDER BY c.created_at ASC
");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$res = $stmt->get_result();

$out = [];
while ($r = $res->fetch_assoc()) {
    $out[] = $r;
}
echo json_encode($out);
