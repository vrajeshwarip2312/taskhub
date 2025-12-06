<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$title = $_POST['title'];
$desc = $_POST['description'];
$priority = $_POST['priority'];
$due = $_POST['due_date'];
$user_id = $_SESSION['user_id'];

// task insert
$stmt = $conn->prepare("
    INSERT INTO tasks (title, description, priority, due_date, created_by)
    VALUES (?,?,?,?,?)
");
$stmt->bind_param("ssssi", $title, $desc, $priority, $due, $user_id);

if ($stmt->execute()) {

    // notification insert
    $msg = "New task created: $title";
    $stmt2 = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?,?)");
    $stmt2->bind_param("is", $user_id, $msg);
    $stmt2->execute();

    header("Location: ../public/tasks.php?created=1");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
