<?php
// actions/add_comment.php
include "../config/auth_check.php";
include "../config/db.php";
include "create_notification.php"; // defines create_notification()

header('Content-Type: application/json');

$task_id = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;
$comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';

if ($task_id <= 0 || $comment === '') {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Insert comment
$stmt = $conn->prepare("INSERT INTO comments (task_id, user_id, comment) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $task_id, $user_id, $comment);

if ($stmt->execute()) {
    $comment_id = $conn->insert_id;

    // Notify the assigned user and task creator (if different)
    $tstmt = $conn->prepare("SELECT created_by, assigned_to, title FROM tasks WHERE id = ?");
    $tstmt->bind_param("i", $task_id);
    $tstmt->execute();
    $tres = $tstmt->get_result();
    if ($trow = $tres->fetch_assoc()) {
        $title = $trow['title'];
        $creator = (int)$trow['created_by'];
        $assigned = (int)$trow['assigned_to'];

        $msg = $_SESSION['name'] . " commented on task: " . $title;

        // Notify assigned user (if exists and not the commenter)
        if ($assigned && $assigned !== $user_id) {
            create_notification($conn, $assigned, $msg);
        }
        // Notify creator if different and not commenter
        if ($creator && $creator !== $user_id && $creator !== $assigned) {
            create_notification($conn, $creator, $msg);
        }
    }

    echo json_encode(['success' => true, 'comment_id' => $comment_id]);
} else {
    echo json_encode(['success' => false, 'error' => $stmt->error]);
}
