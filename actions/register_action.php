<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/register.php");
    exit();
}

include "../config/db.php";

// Validate required fields
if (!isset($_POST['name'], $_POST['email'], $_POST['password'])) {
    die("Required fields missing!");
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $pass);

if ($stmt->execute()) {
    header("Location: ../public/login.php?registered=1");
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>
