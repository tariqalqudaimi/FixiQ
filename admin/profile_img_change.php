<?php
session_start();
require_once 'db.php';
ob_start();

if (!isset($_GET['id'])) {
    header('location: profile.php');
    exit();
}

$id = base64_decode($_GET['id']);

if (isset($_POST['photo_submit'])) {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        
        if ($_FILES['photo']['size'] > 2000000) {
            $_SESSION['profile_photo_error'] = "File is too large. Please upload under 2MB.";
            header('location: profile.php');
            exit();
        }

        $photo = $_FILES['photo']['name'];
        $photo_ext = strtolower(pathinfo($photo, PATHINFO_EXTENSION));
        $photo_name = $id . "." . $photo_ext;
        
        $accepted_extensions = ['png', 'jpg', 'jpeg'];
        if (!in_array($photo_ext, $accepted_extensions)) {
            $_SESSION['profile_photo_error'] = "Invalid file type. Please upload a JPG, PNG, or JPEG.";
            header('location: profile.php');
            exit();
        }

        // Get old photo to delete it
        $stmt = $dbcon->prepare("SELECT photo FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($old_photo_row = $result->fetch_assoc()) {
            $old_photo_name = $old_photo_row['photo'];
            if ($old_photo_name != 'default.png' && file_exists("image/users/" . $old_photo_name)) {
                unlink("image/users/" . $old_photo_name);
            }
        }
        
        // Update database with new photo name
        $update_stmt = $dbcon->prepare("UPDATE users SET photo=? WHERE id=?");
        $update_stmt->bind_param("si", $photo_name, $id);

        if ($update_stmt->execute()) {
            move_uploaded_file($_FILES['photo']['tmp_name'], "image/users/" . $photo_name);
            $_SESSION['profile_photo_success'] = "Photo updated successfully!";
        } else {
             $_SESSION['profile_photo_error'] = "Database update failed.";
        }

    } else {
        $_SESSION['profile_photo_error'] = "Please choose a photo to upload.";
    }
}

header('location: profile.php');
exit();
?>