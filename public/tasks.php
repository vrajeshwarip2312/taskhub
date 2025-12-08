<?php
require_once __DIR__ . '/../config/auth_check.php';
require_once __DIR__ . '/../config/db.php';
include __DIR__ . '/header.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT t.*, u.name AS assigned_user 
        FROM tasks t
        LEFT JOIN users u ON t.assigned_to = u.id
        WHERE t.created_by = ? OR t.assigned_to = ?
        ORDER BY t.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between mb-3">
        <h3>Your Tasks</h3>
        <a href="add_task.php" class="btn btn-primary btn-sm">+ Add Task</a>
    </div>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Assigned To</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

        <?php if ($result->num_rows == 0): ?>
            <tr>
                <td colspan="7" class="text-center text-muted">No tasks found.</td>
            </tr>

        <?php else: ?>
            
            <?php $sl = 1; ?>

            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $sl++ ?></td>

                    <td><?= htmlspecialchars($row['title']) ?></td>

                    <td>
                        <?php if ($row['priority'] === 'High'): ?>
                            <span class="badge bg-danger">High</span>
                        <?php elseif ($row['priority'] === 'Medium'): ?>
                            <span class="badge bg-warning text-dark">Medium</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Low</span>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($row['status'] === 'Completed'): ?>
                            <span class="badge bg-success">Completed</span>
                        <?php elseif ($row['status'] === 'In Progress'): ?>
                            <span class="badge bg-info text-dark">In Progress</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Pending</span>
                        <?php endif; ?>
                    </td>

                    <td><?= $row['assigned_user'] ?: 'Unassigned' ?></td>
                    <td><?= $row['due_date'] ?: '-' ?></td>

                    <td>
                        <a href="view_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-info">View</a>
                        <a href="edit_task.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>

                        <button 
                            class="btn btn-sm btn-danger btn-delete"
                            data-id="<?= $row['id'] ?>">
                            Delete
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php endif; ?>

        </tbody>
    </table>
</div>

<?php include __DIR__ . '/footer.php'; ?>

<script>
function showNotification(type, message){
    const box = document.createElement("div");
    box.className = "notify " + type;
    box.innerText = message;
    document.body.appendChild(box);
    setTimeout(()=> box.remove(), 2500);
}
</script>


<!-- DELETE TASK AJAX -->
<script>
$(".btn-delete").on("click", function(e){
    e.preventDefault();

    const id = $(this).data("id");

    if(!confirm("Delete this task?")) return;

    $.post("/taskhub/actions/delete_task.php", { id:id }, function(res){

        if(res.trim() === "success"){
            showNotification("error", "Task Deleted");
            setTimeout(()=> location.reload(), 700);
        } 
        else {
            showNotification("error", "Delete failed!");
            console.log(res);
        }

    });

});
</script>
