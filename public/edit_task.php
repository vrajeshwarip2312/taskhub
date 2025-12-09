<?php include "../config/auth_check.php"; ?>
<?php include "../config/db.php"; ?>
<?php include "header.php"; ?>

<?php
// Get task details
$id = $_GET['id'];
$q = $conn->prepare("SELECT * FROM tasks WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$t = $q->get_result()->fetch_assoc();

// Fetch users for Assigned To dropdown
$users = $conn->query("SELECT id, name FROM users");
?>

<div class="container mt-4">
    <h3>Edit Task</h3>

    <form method="POST" action="../actions/edit_task_action.php">
        <input type="hidden" name="id" value="<?= $t['id'] ?>">

        <label>Title</label>
        <input type="text" name="title" value="<?= $t['title'] ?>" class="form-control">

        <label>Description</label>
        <textarea name="description" class="form-control"><?= $t['description'] ?></textarea>

        <label>Status</label>
        <select name="status" class="form-control">
            <option value="Pending" <?= $t['status']=="Pending"?"selected":"" ?>>Pending</option>
            <option value="In Progress" <?= $t['status']=="In Progress"?"selected":"" ?>>In Progress</option>
            <option value="Completed" <?= $t['status']=="Completed"?"selected":"" ?>>Completed</option>
        </select>

        <label>Priority</label>
        <select name="priority" class="form-control">
            <option value="Low" <?= $t['priority']=="Low"?"selected":"" ?>>Low</option>
            <option value="Medium" <?= $t['priority']=="Medium"?"selected":"" ?>>Medium</option>
            <option value="High" <?= $t['priority']=="High"?"selected":"" ?>>High</option>
        </select>

        <label>Due Date</label>
        <input type="date" name="due_date" value="<?= $t['due_date'] ?>" class="form-control">

        <label>Assigned To</label>
        <select name="assigned_to" class="form-control">
            <option value="">-- Unassigned --</option>

            <?php while ($u = $users->fetch_assoc()) { ?>
                <option value="<?= $u['id'] ?>" 
                    <?= ($t['assigned_to'] == $u['id']) ? "selected" : "" ?>>
                    <?= $u['name'] ?>
                </option>
            <?php } ?>
        </select>

        <button class="btn btn-primary mt-3">Update</button>
        <a class="btn btn-secondary mt-3" href="tasks.php">Cancel</a>
    </form>

</div>

<?php include "footer.php"; ?>
