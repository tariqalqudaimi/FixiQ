<?php
// services_edit.php

// --- SETUP AND SECURITY ---
require_once "../UserSetting/user_auth.php"; 
require_once '../Database/db.php';
ob_start(); // Used to prevent header errors

// --- HANDLE THE FORM SUBMISSION (UPDATE LOGIC) ---
if (isset($_POST['update_service'])) {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $title_ar = $_POST['title_ar'];
    $description = $_POST['description'];
    $description_ar = $_POST['description_ar'];
    $box_color_class = $_POST['box_color_class'];

    // Get the current image filename from the database
    $stmt_img = $dbcon->prepare("SELECT image_file FROM services WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $current_image = $stmt_img->get_result()->fetch_assoc()['image_file'];
    $stmt_img->close();

    $new_image_name = $current_image; // Assume the image is not changed

    // Check if a new image was uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Delete the old image if it wasn't the default one
        if ($current_image != 'default.png') {
            $old_filepath = '../../assets/img/services/' . $current_image;
            if (file_exists($old_filepath)) {
                unlink($old_filepath);
            }
        }
        // Upload the new image
        $target_dir = "../../assets/img/services/";
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = "service-" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_image_name);
    }

    // Update the database with all the new information
    $stmt_update = $dbcon->prepare("UPDATE services SET title = ?, title_ar = ?, description = ?, description_ar = ?, image_file = ?, box_color_class = ? WHERE id = ?");
    $stmt_update->bind_param("ssssssi", $title, $title_ar, $description, $description_ar, $new_image_name, $box_color_class, $id);
    $stmt_update->execute();
    $stmt_update->close();

    // Redirect back to the main services list
    header('Location: manage_services.php');
    exit();
}

// --- GET THE DATA FOR THE SERVICE TO BE EDITED ---
// Check if an ID was passed in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_services.php');
    exit();
}
$id_to_edit = (int)$_GET['id'];

// Fetch the service's data from the database
$stmt = $dbcon->prepare("SELECT * FROM services WHERE id = ?");
$stmt->bind_param("i", $id_to_edit);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();
$stmt->close();

// If no service with that ID exists, redirect back
if (!$service) {
    header('Location: manage_services.php');
    exit();
}

// --- PAGE SETUP AND HTML FORM ---
$title = "Edit Service";
require_once "../Dashboard/header.php"; 
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Service: <?= htmlspecialchars($service['title']) ?></h4>
    </div>
    <div class="card-body">
        <form action="services_edit.php?id=<?= $id_to_edit ?>" method="post" enctype="multipart/form-data">
            <!-- Hidden input to pass the ID during submission -->
            <input type="hidden" name="id" value="<?= $id_to_edit ?>">

            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Title (English)</label><input type="text" class="form-control" name="title" value="<?= htmlspecialchars($service['title']) ?>" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Title (Arabic)</label><input type="text" class="form-control" name="title_ar" value="<?= htmlspecialchars($service['title_ar']) ?>" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Description (English)</label><textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($service['description']) ?></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>Description (Arabic)</label><textarea class="form-control" name="description_ar" rows="3" required><?= htmlspecialchars($service['description_ar']) ?></textarea></div></div>
            </div>
            
            <div class="form-group">
                <label>Current Icon/Image</label>
                <div>
                    <img src="../../assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="Current Image" style="width: 50px; height: 50px; background: #eee; padding: 5px; border-radius: 5px; margin-bottom: 10px;">
                </div>
                <label>Upload New Icon/Image (Optional)</label>
                <input type="file" class="form-control-file" name="image">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>

            <div class="form-group">
                <label>Box Color</label>
                <select name="box_color_class" class="form-control">
                    <?php $colors = ['blue', 'orange', 'green', 'pink', 'yellow', 'red', 'teal', 'purple']; ?>
                    <?php foreach ($colors as $color) : ?>
                        <option value="iconbox-<?= $color ?>" <?= ($service['box_color_class'] == 'iconbox-'.$color) ? 'selected' : '' ?>>
                            <?= ucfirst($color) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <hr>
            <div class="text-right">
                 <a href="manage_services.php" class="btn btn-secondary">Cancel</a>
                 <button type="submit" name="update_service" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>