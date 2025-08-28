<?php
// user_auth.php should check if the user is logged in.
require_once "user_auth.php"; 
require_once "db.php";

// Check if an ID is provided in the URL
if(isset($_GET['id'])) {
    $member_id = intval($_GET['id']); // Sanitize to prevent SQL injection

    // First, get the image filename to delete it from the server
    $stmt_select = $dbcon->prepare("SELECT image_file FROM team_members WHERE id = ?");
    $stmt_select->bind_param("i", $member_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    if($row = $result->fetch_assoc()) {
        $image_to_delete = $row['image_file'];
        $file_path = "../assets/img/team/" . $image_to_delete;
        if(file_exists($file_path) && $image_to_delete !== 'default_avatar.png') {
            unlink($file_path); // Delete the image file
        }
    }
    $stmt_select->close();

    // Now, delete the record from the database
    $stmt_delete = $dbcon->prepare("DELETE FROM team_members WHERE id = ?");
    $stmt_delete->bind_param("i", $member_id);
    $stmt_delete->execute();
    $stmt_delete->close();
}

// Redirect back to the manage team page
header('Location: manage_team.php');
exit();
?>