<?php
require_once '../Database/db.php';

if (isset($_GET['id'])) {
    $id = base64_decode($_GET['id']);
    $stmt = $dbcon->prepare("UPDATE users SET status=1 WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('location: ../UserSetting/users.php');
exit();
?>