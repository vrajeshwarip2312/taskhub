<?php
session_start();
require_once "../config/auth_check.php";
require_once "../config/db.php";

requireLogin();

$user_id = $_SESSION['user_id'];

// Fetch fresh user info
$q = $conn->prepare("SELECT name, email, avatar FROM users WHERE id=?");
$q->bind_param("i", $user_id);
$q->execute();
$user = $q->get_result()->fetch_assoc();
$q->close();

// Update session
$_SESSION['name'] = $user['name'];
$_SESSION['avatar'] = $user['avatar'] ?: 'default.png';

// Task counts
$total      = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE created_by=$user_id")->fetch_assoc()['c'];
$pending    = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE created_by=$user_id AND status='Pending'")->fetch_assoc()['c'];
$completed  = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE created_by=$user_id AND status='Completed'")->fetch_assoc()['c'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - TaskHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<?php include "header.php"; ?>

<div class="container mt-4">

    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']); ?> 👋</h2>

    <div class="d-flex justify-content-end mb-3">
        <a href="add_task.php" class="btn btn-primary">+ Add Task</a>
    </div>
    
    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h4>Total Tasks</h4>
                <p class="fw-bold fs-4"><?= $total ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h4>Pending Tasks</h4>
                <p class="fw-bold fs-4 text-warning"><?= $pending ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h4>Completed Tasks</h4>
                <p class="fw-bold fs-4 text-success"><?= $completed ?></p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
