<?php
require_once "db.php";

if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']);
    // USE PREPARED STATEMENTS
    $stmt = $dbcon->prepare("UPDATE users SET status=1 WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('location: users.php');
exit();
?>