<?php
require_once "user_auth.php";
require_once "db.php";

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Update the status to 1 (read)
    $stmt = $dbcon->prepare("UPDATE contact_messages SET status = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    // Send a success response
    http_response_code(200);
} else {
    http_response_code(400); // Bad Request
}
?>