<?php
include 'db.php';

$task_id = $_GET['task_id'];

$sql = "SELECT * FROM comments WHERE task_id='$task_id' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

$comments = [];
while ($row = mysqli_fetch_assoc($result)) {
    $comments[] = $row;
}

echo json_encode($comments);
?>
