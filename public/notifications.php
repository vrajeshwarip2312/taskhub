<?php
require_once("../config/auth_check.php");
require_once("../config/db.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT id, message, created_at, is_read 
        FROM notifications 
        WHERE user_id = ? 
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Notifications</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php include("header.php"); ?>

<div class="container mt-4">

    <h3 class="mb-4">Your Notifications</h3>

    <div class="list-group shadow-sm">

        <?php if ($result->num_rows == 0): ?>
            <div class="list-group-item text-center text-muted py-4">
                No notifications found.
            </div>

        <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                
                <div class="list-group-item">

                    <div class="small text-muted">
                        <?= $row['created_at']; ?>
                    </div>

                    <div class="fw-bold">
                        <?= htmlspecialchars($row['message']); ?>
                    </div>

                    <?php if ($row['is_read'] == 0): ?>
                        <form action="../actions/mark_notification_read.php" method="POST" class="mt-2">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button class="btn btn-sm btn-outline-primary">Mark as read</button>
                        </form>

                    <?php else: ?>
                        <span class="badge bg-success mt-2">Read</span>
                    <?php endif; ?>

                </div>

            <?php endwhile; ?>
        <?php endif; ?>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
