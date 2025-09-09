<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $dbcon->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('location: manage_messages.php');
exit();
?>