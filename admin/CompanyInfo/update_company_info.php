<?php
session_start();
require_once '../Database/db.php';ob_start();

if (isset($_POST['submit'])) {

    $current_images_result = $dbcon->query("SELECT hero_image, about_image FROM company_settings WHERE id=1");
    $current_images = $current_images_result->fetch_assoc();

    $hero_image_name = $current_images['hero_image'];
    $about_image_name = $current_images['about_image'];
    
    $upload_dir = "../assets/img/CompanyInfo/"; 
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    function handleUpload($file_input_name, $current_filename, $upload_dir, $prefix) {
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
            if ($current_filename && $current_filename != 'default.png' && file_exists($upload_dir . $current_filename)) {
                unlink($upload_dir . $current_filename);
            }

            $file_ext = pathinfo($_FILES[$file_input_name]['name'], PATHINFO_EXTENSION);
            $new_filename = $prefix . "_" . time() . "." . $file_ext;
            move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $upload_dir . $new_filename);
            
            return $new_filename;
        }
        return $current_filename;
    }

    $new_hero_image_name = handleUpload('hero_image', $hero_image_name, $upload_dir, 'hero');
    $new_about_image_name = handleUpload('about_image', $about_image_name, $upload_dir, 'about');

    $hero_title = $_POST['hero_title'];
    $hero_title_ar = $_POST['hero_title_ar'];
    $hero_subtitle = $_POST['hero_subtitle'];
    $hero_subtitle_ar = $_POST['hero_subtitle_ar'];
    $about_title = $_POST['about_title'];
    $about_subtitle = $_POST['about_subtitle'];
    
    
    $fb_link = $_POST['fb_link'];
    $whatsapp_number = $_POST['whatsapp_number']; 
    $instagram_link = $_POST['instagram_link'];
    $linkedin_link = $_POST['linkedin_link'];
    
    $stmt = $dbcon->prepare(
        "UPDATE company_settings SET 
            hero_title = ?, hero_title_ar = ?, hero_subtitle = ?, hero_subtitle_ar = ?, hero_image = ?,
            about_title = ?, about_subtitle = ?, about_image = ?,
            fb_link = ?, whatsapp_number = ?, instagram_link = ?, linkedin_link = ? 
        WHERE id=1"
    );

    $stmt->bind_param( 
        "ssssssssssss", 
        $hero_title, $hero_title_ar, $hero_subtitle, $hero_subtitle_ar, $new_hero_image_name,
        $about_title, $about_subtitle, $new_about_image_name,
        $fb_link, $whatsapp_number, $instagram_link, $linkedin_link 
    );

    if ($stmt->execute()) {
        $_SESSION['update_success'] = "Website settings updated successfully!";
    } else {
        $_SESSION['update_error'] = "Error updating settings: " . $stmt->error;
    }
    
    header('location: edit_company_info.php');
    exit();

} else {
    header('location: .../Dashboard/index.php');
    exit();
}
?>