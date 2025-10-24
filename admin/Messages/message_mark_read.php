<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    $stmt = $dbcon->prepare("UPDATE contact_messages SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    http_response_code(200);
} else {
    http_response_code(400); 
}
?>