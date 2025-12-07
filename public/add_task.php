<?php
require_once "../config/auth_check.php";
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

        <div class="mb-3">
            <label class="form-label">Task Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Priority</label>
            <select name="priority" class="form-select">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control">
        </div>

        <button class="btn btn-primary" type="submit">Create Task</button>
        <a href="tasks.php" class="btn btn-secondary">Cancel</a>

    </form>

</div>

<?php include "footer.php"; ?>

</body>
</html>
