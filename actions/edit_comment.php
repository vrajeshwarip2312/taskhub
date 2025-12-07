<?php
include "../config/auth_check.php";
include "../config/db.php";

if (!isset($_POST['id']) || !isset($_POST['comment'])) {
    echo json_encode(["status" => "error", "msg" => "Invalid request"]);
    exit;
}

$id = intval($_POST['id']);
$comment = trim($_POST['comment']);
$user_id = $_SESSION['user_id'];

$q = $conn->prepare("UPDATE comments SET comment=? WHERE id=? AND user_id=?");
$q->bind_param("sii", $comment, $id, $user_id);

if ($q->execute()) {
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "msg" => "DB Error"]);
}
?>
