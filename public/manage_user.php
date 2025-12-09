<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require "../config/db.php";          
require "../config/auth_check.php";  

requireLogin();    
requireAdmin();    

$result = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
if (!$result) {
    die("DB Error: " . mysqli_error($conn));
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h2 class="mb-4">Manage Users</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'role_updated'): ?>
    <div class="alert alert-success">Role updated successfully!</div>
<?php endif; ?>

<table class="table table-bordered table-striped mt-3">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>

    <?php while ($user = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= ucfirst($user['role']) ?></td>
            <td>
                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            </td>
        </tr>
    <?php endwhile; ?>

    </tbody>
</table>

</body>
</html>
