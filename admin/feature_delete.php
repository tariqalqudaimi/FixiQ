<?php
// user_auth.php should check if the user is logged in.
require_once "user_auth.php";
require_once "db.php";

// Check if an ID is provided in the URL
if(isset($_GET['id'])) {
    $feature_id = intval($_GET['id']); // Sanitize

    // This table doesn't have associated files, so we just delete the record.
    $stmt = $dbcon->prepare("DELETE FROM features WHERE id = ?");
    $stmt->bind_param("i", $feature_id);
    $stmt->execute();
    $stmt->close();
}

// Redirect back to the manage features page
header('Location: manage_features.php');
exit();
?>