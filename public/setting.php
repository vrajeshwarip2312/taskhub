<?php
include "../config/auth_check.php";
requireAdmin();
include "header.php";
?>

<div class="container mt-4">
    <h3>Admin Settings</h3>
    <p>This is where admin settings will come.</p>

    <a href="users.php" class="btn btn-info mt-3">Manage Users</a>
</div>

<?php include "footer.php"; ?>
