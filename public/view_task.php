<?php include "../config/auth_check.php"; ?>
<?php include "../config/db.php"; ?>
<?php include "header.php"; ?>

<?php
$id = $_GET['id'];
$q = $conn->prepare("SELECT * FROM tasks WHERE id=?");
$q->bind_param("i", $id);
$q->execute();
$t = $q->get_result()->fetch_assoc();
?>

<div class="container mt-4">
<h3>Task Details</h3>

<p><strong>Title:</strong> <?= htmlspecialchars($t['title']) ?></p>
<p><strong>Description:</strong> <?= htmlspecialchars($t['description']) ?></p>
<p><strong>Priority:</strong> <?= $t['priority'] ?></p>
<p><strong>Status:</strong> <?= $t['status'] ?></p>
<p><strong>Due Date:</strong> <?= $t['due_date'] ?></p>

<a href="edit_task.php?id=<?= $t['id'] ?>" class="btn btn-warning">Edit</a>
<a href="tasks.php" class="btn btn-secondary">Back</a>

</div>

<?php include "footer.php"; ?>
