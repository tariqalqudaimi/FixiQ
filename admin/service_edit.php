<?php
require_once "user_auth.php";
ob_start();
$title = "Edit Primary Focus";
require_once "header.php";
require_once "db.php";

// Get the ID of the item to edit
$id_to_edit = $_GET['id'];

// Handle form submission for updating
if (isset($_POST['submit'])) {
    // Update text fields
    $title_en = $_POST['title_en'];
    $some_text_en = $_POST['some_text_en'];
    $title_ar = $_POST['title_ar'];
    $some_text_ar = $_POST['some_text_ar'];
    $update_stmt = $dbcon->prepare("UPDATE services SET title=?, some_text=?, title_ar=?, some_text_ar=? WHERE id=?");
    $update_stmt->bind_param("ssssi", $title_en, $some_text_en, $title_ar, $some_text_ar, $id_to_edit);
    $update_stmt->execute();

    // Handle optional image update
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../admin/image/focus/";
        
        // Get current image to delete it
        $current_img_stmt = $dbcon->prepare("SELECT image_file FROM services WHERE id = ?");
        $current_img_stmt->bind_param("i", $id_to_edit);
        $current_img_stmt->execute();
        $old_image = $current_img_stmt->get_result()->fetch_assoc()['image_file'];
        if ($old_image != 'default.png' && file_exists($target_dir . $old_image)) {
            unlink($target_dir . $old_image);
        }
        
        $extension = strtolower(pathinfo($_FILES['photo']["name"], PATHINFO_EXTENSION));
        $new_filename = "focus-" . time() . '.' . $extension;
        move_uploaded_file($_FILES['photo']["tmp_name"], $target_dir . $new_filename);
        
        $img_stmt = $dbcon->prepare("UPDATE services SET image_file=? WHERE id=?");
        $img_stmt->bind_param("si", $new_filename, $id_to_edit);
        $img_stmt->execute();
    }
    
    $_SESSION['focus_message'] = "Focus area updated successfully!";
    header('Location: manage_focus.php');
    exit();
}

// Fetch the existing data to pre-fill the form
$stmt = $dbcon->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id_to_edit);
$stmt->execute();
$existing_data = $stmt->get_result()->fetch_assoc();
?>

<div class="card">
    <div class="card-header"><h4 class="card-title">Edit Focus Area</h4></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data"> <!-- enctype IS needed here -->
            <div class="row">
                <div class="col-md-6">
                    <h5>English</h5>
                    <div class="form-group">
                        <label>Title (English)</label>
                        <input type="text" class="form-control" name="title_en" value="<?= htmlspecialchars($existing_data['title']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Description (English)</label>
                        <textarea class="form-control" name="some_text_en" rows="3" required><?= htmlspecialchars($existing_data['some_text']) ?></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Arabic</h5>
                    <div class="form-group">
                        <label>Title (Arabic)</label>
                        <input type="text" class="form-control" name="title_ar" dir="rtl" value="<?= htmlspecialchars($existing_data['title_ar']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Description (Arabic)</label>
                        <textarea class="form-control" name="some_text_ar" rows="3" dir="rtl"><?= htmlspecialchars($existing_data['title_ar']) ?></textarea>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label>Update Icon/Image (Optional)</label>
                <input type="file" class="form-control-file" name="photo">
                <small class="form-text text-muted">Current: <img src="../image/focus/<?= htmlspecialchars($existing_data['image_file']) ?>" width="30"></small>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<?php require_once "footer.php"; ?>