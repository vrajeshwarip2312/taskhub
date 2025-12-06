<?php
session_start();
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

$conn->query("DELETE FROM notifications WHERE user_id=$user_id");

header("Location: ../public/notifications.php");
exit;
?>
