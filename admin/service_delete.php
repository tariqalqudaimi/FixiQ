<?php
require_once "user_auth.php";
require_once "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // First, get the filename to delete the file from the server
    $stmt = $dbcon->prepare("SELECT image_file FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    if ($result) {
        $filepath = '../admin/image/focus/' . $result['image_file'];
        if (file_exists($filepath)) {
            unlink($filepath); // Delete the image file
        }
    }

    // Now, delete the record from the database
    $stmt = $dbcon->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirect back to the main management page
header('location: manage_focus.php');
exit();
?>