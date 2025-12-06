<?php
include "../config/auth_check.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

// Total Tasks
$total = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE created_by=$user_id")->fetch_assoc()['c'];

// Pending Tasks
$pending = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE created_by=$user_id AND status='Pending'")->fetch_assoc()['c'];

// Completed Tasks
$completed = $conn->query("SELECT COUNT(*) AS c FROM tasks WHERE created_by=$user_id AND status='Completed'")->fetch_assoc()['c'];
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

    <h2>Welcome, <?php echo $_SESSION['name']; ?> 👋</h2>

    <div class="d-flex justify-content-end mb-3">
        <a href="add_task.php" class="btn btn-primary">+ Add Task</a>
    </div>

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h4>Total Tasks</h4>
                <p class="fw-bold fs-4"><?php echo $total; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h4>Pending Tasks</h4>
                <p class="fw-bold fs-4 text-warning"><?php echo $pending; ?></p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow p-3">
                <h4>Completed Tasks</h4>
                <p class="fw-bold fs-4 text-success"><?php echo $completed; ?></p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
