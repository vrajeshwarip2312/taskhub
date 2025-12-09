<?php
include "../config/auth_check.php";
requireAdmin();
include "header.php";
include "../config/db.php";

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="container mt-4">
    <h3>All Users</h3>
    <table class="table table-bordered mt-3">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while ($u = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= $u['name'] ?></td>
            <td><?= $u['email'] ?></td>
            <td><?= $u['role'] ?></td>
            <td>
                <a href="manage_users.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-primary">
                    Manage
                </a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>

<?php include "footer.php"; ?>
