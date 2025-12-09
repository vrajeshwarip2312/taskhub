<?php
session_start();
require "../config/db.php";

$uid     = $_SESSION["user_id"];
$current = $_POST["current_password"];
$new     = $_POST["new_password"];
$confirm = $_POST["confirm_password"];


if ($new !== $confirm) {
    die("New passwords do not match!");
}

$q = $conn->prepare("SELECT password FROM users WHERE id=?");
$q->bind_param("i", $uid);
$q->execute();
$q->bind_result($hashedPassword);
$q->fetch();
$q->close();

if (!password_verify($current, $hashedPassword)) {
    die("Current password is incorrect!");
}

// Update new password
$newHashed = password_hash($new, PASSWORD_DEFAULT);

$u = $conn->prepare("UPDATE users SET password=? WHERE id=?");
$u->bind_param("si", $newHashed, $uid);
$u->execute();
$u->close();

header("Location: ../public/profile.php?updated=1");
exit;
?>
