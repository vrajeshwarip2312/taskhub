<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isUser() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'user';
}

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /taskhub/public/login.php");
        exit;
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: /taskhub/public/dashboard.php");
        exit;
    }
}
?>
