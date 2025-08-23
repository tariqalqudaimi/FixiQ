<?php
require_once "user_auth.php";
require_once "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Get the filename to delete from server
    $stmt = $dbcon->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result) {
        $filepath = '../assets/img/portfolio/' . $result['image'];
        if ($result['image'] != 'default.png' && file_exists($filepath)) {
            unlink($filepath);
        }
    }

    // Delete the record from the database
    $stmt = $dbcon->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header('location: manage_products.php');
exit();
?>