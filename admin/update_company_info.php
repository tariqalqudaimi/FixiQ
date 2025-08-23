<?php
session_start();
require_once 'db.php';
ob_start();

// This script now handles a single form submission for all company settings.
if (isset($_POST['submit'])) {

    // --- 1. HANDLE IMAGE UPLOADS FIRST ---

    // Fetch the current image names from the database to delete them if new ones are uploaded.
    $current_images_result = $dbcon->query("SELECT hero_image, about_image FROM company_settings WHERE id=1");
    $current_images = $current_images_result->fetch_assoc();

    $hero_image_name = $current_images['hero_image'];
    $about_image_name = $current_images['about_image'];
    
    $upload_dir = "../assets/img/"; // The directory for frontend images.
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // Function to handle a single file upload
    function handleUpload($file_input_name, $current_filename, $upload_dir, $prefix) {
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
            // A new file was uploaded, so delete the old one.
            if ($current_filename && $current_filename != 'default.png' && file_exists($upload_dir . $current_filename)) {
                unlink($upload_dir . $current_filename);
            }

            // Create a new unique name and upload the file.
            $file_ext = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION);
            $new_filename = $prefix . "_" . time() . "." . $file_ext;
            move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $upload_dir . $new_filename);
            
            // Return the new filename to be saved in the database.
            return $new_filename;
        }
        // If no new file was uploaded, keep the old filename.
        return $current_filename;
    }

    // Process the hero and about images
    $new_hero_image_name = handleUpload('hero_image', $hero_image_name, $upload_dir, 'hero');
    $new_about_image_name = handleUpload('about_image', $about_image_name, $upload_dir, 'about');


    // --- 2. HANDLE TEXT AND LINK FIELDS ---
    
    // Get all text fields from the POST request
    $hero_title = $_POST['hero_title'];
    $hero_title_ar = $_POST['hero_title_ar'];
    $hero_subtitle = $_POST['hero_subtitle'];
    $hero_subtitle_ar = $_POST['hero_subtitle_ar'];
    $about_title = $_POST['about_title'];
    $about_subtitle = $_POST['about_subtitle'];
    $about_bullet1 = $_POST['about_bullet1'];
    $about_bullet2 = $_POST['about_bullet2'];
    $about_bullet3 = $_POST['about_bullet3'];
    $fb_link = $_POST['fb_link'];
    $twitter_link = $_POST['twitter_link'];
    $instagram_link = $_POST['instagram_link'];
    $linkedin_link = $_POST['linkedin_link'];
    
    // You can add company_name and logo_path here if you add them to the form.
    // For now, we'll focus on what's in the edit_company_info.php form.


    // --- 3. UPDATE THE DATABASE WITH ALL NEW DATA ---

    // The SQL query is now much cleaner and matches the `company_settings` table.
      $stmt = $dbcon->prepare(
        "UPDATE company_settings SET 
            hero_title = ?,hero_title_ar = ? ,hero_subtitle = ?,hero_subtitle_ar = ?, hero_image = ?,
            about_title = ?, about_subtitle = ?, about_image = ?,
            about_bullet1 = ?, about_bullet2 = ?, about_bullet3 = ?,
            fb_link = ?, twitter_link = ?, instagram_link = ?, linkedin_link = ?
        WHERE id=1"
    );

   // الشيفرة الصحيحة
$stmt->bind_param( 
    "sssssssssssssss", // <-- أضفنا 15 حرف 's' هنا
    $hero_title, $hero_title_ar, $hero_subtitle, $hero_subtitle_ar, $new_hero_image_name,
    $about_title, $about_subtitle, $new_about_image_name,
    $about_bullet1, $about_bullet2, $about_bullet3,
    $fb_link, $twitter_link, $instagram_link, $linkedin_link
);
    if ($stmt->execute()) {
        $_SESSION['update_success'] = "Website settings updated successfully!";
    } else {
        $_SESSION['update_error'] = "Error updating settings: " . $stmt->error;
    }
    
    // Redirect back to the editing page.
    header('location: edit_company_info.php');
    exit();

} else {
    // If someone accesses this page directly without submitting the form, redirect them.
    header('location: index.php');
    exit();
}
?>