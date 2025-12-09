<?php
session_start();
require "../config/auth_check.php";
requireLogin();
require "../config/db.php";

// Fetch all users (except current user optional)
$users = mysqli_query($conn, "SELECT id, name FROM users ORDER BY name");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task - TaskHub</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<?php include "header.php"; ?>

<div class="container mt-4">

    <h2>Create New Task</h2>

    <form action="../actions/add_task_action.php" method="POST">

        <input type="hidden" name="status" value="Pending">

        <!-- Title -->
        <div class="mb-3">
            <label class="form-label">Task Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Assign To -->
        <div class="mb-3">
            <label class="form-label">Assign To</label>
            <select name="assigned_to" class="form-select">
                <option value="">Unassigned</option>

                <?php while ($u = mysqli_fetch_assoc($users)) : ?>
                    <option value="<?= $u['id'] ?>">
                        <?= htmlspecialchars($u['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Priority -->
        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <!-- Due Date -->
        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control">
        </div>

        <button class="btn btn-primary">Add Task</button>
        <a href="tasks.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

</body>
</html>
