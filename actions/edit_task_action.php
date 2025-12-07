<?php
session_start();
require "../config/db.php";

if (!isset($_POST['id'])) {
    die("Invalid Request");
}

$id       = $_POST['id'];
$title    = $_POST['title'];
$desc     = $_POST['description'];
$priority = $_POST['priority'];
$status   = $_POST['status'];
$due      = $_POST['due_date'];

$q = $conn->prepare("UPDATE tasks 
                     SET title=?, description=?, priority=?, status=?, due_date=?
                     WHERE id=?");
$q->bind_param("sssssi", $title, $desc, $priority, $status, $due, $id);

if ($q->execute()) {

    $uid = $_SESSION["user_id"];
    $msg = "Task updated: $title (Status: $status)";

    $n = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)");
    $n->bind_param("is", $uid, $msg);
    $n->execute();

    header("Location: ../public/tasks.php?updated=1");
    exit;
}

echo "Error updating record: " . $q->error;
?>
