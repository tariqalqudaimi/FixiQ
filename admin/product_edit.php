<?php
require_once "user_auth.php";
ob_start();
$title = "Edit Project";
require_once "header.php";
require_once "db.php";

$id_to_edit = $_GET['id'];

if (isset($_POST['submit'])) {
    // Update text fields
    $works_name_en = $_POST['works_name_en']; $catagory_en = $_POST['catagory_en']; $stack_en = $_POST['stack_en'];
    $works_name_ar = $_POST['works_name_ar']; $catagory_ar = $_POST['catagory_ar']; $stack_ar = $_POST['stack_ar'];
    $preview_link = $_POST['preview_link']; $github_link = $_POST['github_link']; $figma_link = $_POST['figma_link'];
    
    $update_stmt = $dbcon->prepare("UPDATE my_best_works SET works_name=?, catagory=?, development_stack=?, preview_link=?, github_link=?, figma_link=?, works_name_ar=?, catagory_ar=?, development_stack_ar=? WHERE id=?");
    $update_stmt->bind_param("sssssssssi", $works_name_en, $catagory_en, $stack_en, $preview_link, $github_link, $figma_link, $works_name_ar, $catagory_ar, $stack_ar, $id_to_edit);
    $update_stmt->execute();

    // Handle optional image update
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "../admin/image/my_best_works/";
        
        $current_img_stmt = $dbcon->prepare("SELECT photo FROM my_best_works WHERE id = ?");
        $current_img_stmt->bind_param("i", $id_to_edit);
        $current_img_stmt->execute();
        $old_image = $current_img_stmt->get_result()->fetch_assoc()['photo'];
        if ($old_image != 'default.png' && file_exists($target_dir . $old_image)) {
            unlink($target_dir . $old_image);
        }
        
        $extension = strtolower(pathinfo($_FILES['photo']["name"], PATHINFO_EXTENSION));
        $new_filename = "project-" . time() . '.' . $extension;
        move_uploaded_file($_FILES['photo']["tmp_name"], $target_dir . $new_filename);
        
        $img_stmt = $dbcon->prepare("UPDATE my_best_works SET photo=? WHERE id=?");
        $img_stmt->bind_param("si", $new_filename, $id_to_edit);
        $img_stmt->execute();
    }
    
    $_SESSION['project_message'] = "Project updated successfully!";
    header('Location: manage_projects.php');
    exit();
}

// Fetch the existing data to pre-fill the form
$stmt = $dbcon->prepare("SELECT * FROM my_best_works WHERE id = ?");
$stmt->bind_param("i", $id_to_edit);
$stmt->execute();
$existing_data = $stmt->get_result()->fetch_assoc();
?>

<div class="card">
    <div class="card-header"><h4 class="card-title">Edit Project</h4></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <h5>English</h5>
                    <div class="form-group"><label>Project Name (EN)</label><input type="text" class="form-control" name="works_name_en" value="<?= htmlspecialchars($existing_data['works_name']) ?>" required></div>
                    <div class="form-group"><label>Category (EN)</label><input type="text" class="form-control" name="catagory_en" value="<?= htmlspecialchars($existing_data['catagory']) ?>" required></div>
                    <div class="form-group"><label>Development Stack (EN)</label><input type="text" class="form-control" name="stack_en" value="<?= htmlspecialchars($existing_data['development_stack']) ?>"></div>
                </div>
                <div class="col-md-6">
                    <h5>Arabic</h5>
                    <div class="form-group"><label>Project Name (AR)</label><input type="text" class="form-control" name="works_name_ar" dir="rtl" value="<?= htmlspecialchars($existing_data['works_name_ar']) ?>"></div>
                    <div class="form-group"><label>Category (AR)</label><input type="text" class="form-control" name="catagory_ar" dir="rtl" value="<?= htmlspecialchars($existing_data['catagory_ar']) ?>"></div>
                    <div class="form-group"><label>Development Stack (AR)</label><input type="text" class="form-control" name="stack_ar" dir="rtl" value="<?= htmlspecialchars($existing_data['development_stack_ar']) ?>"></div>
                </div>
            </div>
            <hr>
            <h5>Links</h5>
            <div class="form-group"><label>Preview Link</label><input type="url" class="form-control" name="preview_link" value="<?= htmlspecialchars($existing_data['preview_link']) ?>"></div>
            <div class="form-group"><label>GitHub Link</label><input type="url" class="form-control" name="github_link" value="<?= htmlspecialchars($existing_data['github_link']) ?>"></div>
            <div class="form-group"><label>Figma Link</label><input type="url" class="form-control" name="figma_link" value="<?= htmlspecialchars($existing_data['figma_link']) ?>"></div>
            <hr>
            <div class="form-group">
                <label>Update Project Image (Optional)</label>
                <input type="file" class="form-control-file" name="photo">
                <small class="form-text text-muted">Current: <img src="../admin/image/my_best_works/<?= htmlspecialchars($existing_data['photo']) ?>" width="100"></small>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</div>

<?php require_once "footer.php"; ?>