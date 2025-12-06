<?php
session_start();
require_once __DIR__ . '/../config/db.php';

$task_id = $_POST['task_id'];
$assigned_to = $_POST['assigned_to'];
$assigned_by = $_SESSION['user_id'];

// insert into task_assign table
$stmt = $conn->prepare("INSERT INTO task_assign(task_id, assigned_to, assigned_by) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $task_id, $assigned_to, $assigned_by);
$stmt->execute();

// fetch task title for notification
$t = $conn->prepare("SELECT title FROM tasks WHERE id=?");
$t->bind_param("i", $task_id);
$t->execute();
$title = $t->get_result()->fetch_assoc()['title'];

// notification to assigned user
$message = "You have been assigned a new task: '$title'";

$n = $conn->prepare("INSERT INTO notifications(user_id, message) VALUES (?, ?)");
$n->bind_param("is", $assigned_to, $message);
$n->execute();

header("Location: ../public/dashboard.php?assigned=1");
exit;
