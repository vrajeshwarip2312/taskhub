<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";


if (!isset($_POST['id'])) {
    echo "error";
    exit;
}

$id = $_POST['id'];


$q = $conn->prepare("SELECT title FROM tasks WHERE id=? LIMIT 1");
$q->bind_param("i", $id);
$q->execute();
$res = $q->get_result();

if ($res->num_rows == 0) {
    echo "error";
    exit;
}

$task = $res->fetch_assoc();
$taskTitle = $task['title'];

// DELETE task
$stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    // ADD notification
    $uid = $_SESSION['user_id'];
    $msg = "Task deleted: $taskTitle";

    $n = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)");
    $n->bind_param("is", $uid, $msg);
    $n->execute();

    echo "success";
    exit;
}

echo "error";
?>
