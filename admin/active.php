<?php
require_once "db.php";

if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']);
    $stmt = $dbcon->prepare("UPDATE users SET status=2 WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('location: users.php');
exit();
?>