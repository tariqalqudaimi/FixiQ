<?php
require_once "user_auth.php";
require_once "db.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $dbcon->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirect back to the inbox
header('location: manage_messages.php');
exit();
?>