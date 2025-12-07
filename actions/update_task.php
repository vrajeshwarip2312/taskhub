<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

if (!isset($_POST['id'])) {
    die("Invalid request");
}

$id       = $_POST['id'];
$title    = $_POST['title'];
$desc     = $_POST['description'];
$priority = $_POST['priority'];
$status   = $_POST['status'];       
$due_date = $_POST['due_date'];

//update task
$stmt = $conn->prepare("
    UPDATE tasks 
    SET title=?, description=?, priority=?, status=?, due_date=?
    WHERE id=?
");
$stmt->bind_param("sssssi", $title, $desc, $priority, $status, $due_date, $id);

if ($stmt->execute()) {

    // Create notification
    $msg = "Task updated: $title";
    $uid = $_SESSION["user_id"];

    $n = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)");
    $n->bind_param("is", $uid, $msg);
    $n->execute();

    header("Location: ../public/tasks.php?updated=1");
    exit;
}

echo "Error: " . $stmt->error;
?>
