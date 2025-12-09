<?php
session_start();

require "../config/db.php";          
require "../config/auth_check.php";  

requireLogin();
requireAdmin();


if (!isset($_GET['id'])) {
    die("User ID not provided.");
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    die("User not found.");
}


if (isset($_POST['update_role'])) {
    $role = $_POST['role'];

    if ($role !== 'admin' && $role !== 'user') {
        $error = "Invalid role selected.";
    } else {
        mysqli_query($conn, "UPDATE users SET role = '$role' WHERE id = $id");

    
        header("Location: manage_user.php?msg=role_updated");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User Role</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="container mt-5">

<h2>Edit User Role</h2>

<div class="card p-4 mt-3">
    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Select Role:</label>
        <select name="role" class="form-control mb-3">
            <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <button name="update_role" class="btn btn-primary w-100">
            Update Role
        </button>
    </form>
</div>

</body>
</html>
