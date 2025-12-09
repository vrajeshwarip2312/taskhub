<?php
session_start();
require "../config/db.php";

$uid  = $_SESSION["user_id"];
$name = $_POST["name"];
$email = $_POST["email"];

$avatarName = null;

if (!empty($_FILES["avatar"]["name"])) {

   
    $avatarName = time() . "_" . basename($_FILES["avatar"]["name"]);

   
    $target = "../public/uploads/avatars/" . $avatarName;

    
    if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target)) {
        die("Error uploading file!");
    }

    
    $q = $conn->prepare("UPDATE users SET name=?, email=?, avatar=? WHERE id=?");
    $q->bind_param("sssi", $name, $email, $avatarName, $uid);

} else {

    
    $q = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $q->bind_param("ssi", $name, $email, $uid);
}

$q->execute();
$q->close();

// Update session (for header menu)
$_SESSION["name"] = $name;
$_SESSION["email"] = $email;

if ($avatarName) {
    $_SESSION["avatar"] = $avatarName;
}


header("Location: ../public/dashboard.php?updated=1");
exit;

?>
