<?php
session_start();
require "../config/db.php";

$title = trim($_POST['title']);
$desc  = trim($_POST['description']);
$priority = $_POST['priority'];
$due = $_POST['due_date'];
$status = "Pending"; // 👈 DEFAULT STATUS
$created_by = $_SESSION['user_id'];

$q = $conn->prepare("INSERT INTO tasks(title, description, priority, status, due_date, created_by) 
                     VALUES(?,?,?,?,?,?)");

$q->bind_param("sssssi", $title, $desc, $priority, $status, $due, $created_by);
$q->execute();

header("Location: ../public/tasks.php");
?>
