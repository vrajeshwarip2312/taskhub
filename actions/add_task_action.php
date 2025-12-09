<?php
session_start();
require "../config/db.php";

require_once "../config/auth_check.php";
requireLogin();

$title       = trim($_POST['title']);
$description = trim($_POST['description']);
$priority    = $_POST['priority'];
$due_date    = $_POST['due_date'];
$status      = "Pending";
$created_by  = $_SESSION['user_id'];

$assigned_to = !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : null;

$sql = "INSERT INTO tasks (title, description, priority, due_date, status, created_by, assigned_to)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssis",
    $title,
    $description,
    $priority,
    $due_date,
    $status,
    $created_by,
    $assigned_to
);

$stmt->execute();

header("Location: ../public/tasks.php");
exit;
?>
