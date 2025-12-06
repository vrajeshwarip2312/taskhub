<?php
session_start();
include "../config/db.php";

$email = $_POST['email'];
$pass  = $_POST['password'];

$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    $stmt->bind_result($id, $name, $email, $hash, $role);
    $stmt->fetch();

    if (password_verify($pass, $hash)) {

        $_SESSION['user_id'] = $id;
        $_SESSION['name']    = $name;
        $_SESSION['role']    = $role;

        header("Location: ../public/dashboard.php");
        exit;

    } else {
        echo "Invalid Password!";
    }

} else {
    echo "Email not found!";
}

?>
