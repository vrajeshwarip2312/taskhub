<?php
session_start();
require "../config/db.php";

$uid = $_SESSION["user_id"];

$q = $conn->prepare("SELECT name, email, avatar FROM users WHERE id=?");
$q->bind_param("i", $uid);
$q->execute();
$q->bind_result($name, $email, $avatar);
$q->fetch();
$q->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - TaskHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .avatar-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-4">
    <h2>My Profile</h2>

    <?php if (isset($_GET['updated'])): ?>
        <div class="alert alert-success">Profile updated successfully!</div>
    <?php endif; ?>

    <form action="../actions/update_profile.php" method="POST" enctype="multipart/form-data" class="mb-4">

        <div class="mb-3">
            <label>Name:</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label>Current Avatar:</label><br>

           
            <img src="../public/uploads/avatars/<?= htmlspecialchars($avatar ?: 'default.png'); ?>" 
                 class="avatar-img" alt="Avatar">
        </div>

        <div class="mb-3">
            <label>Upload New Avatar:</label>

           
            <input type="file" name="avatar" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>

    <hr>

    <h2>Change Password</h2>

    <form action="../actions/update_password.php" method="POST">
        <div class="mb-3">
            <label>Current Password:</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>New Password:</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Confirm New Password:</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Change Password</button>
    </form>
</div>

</body>
</html>
