<?php

require_once "../UserSetting/user_auth.php"; 
require_once '../Database/db.php';
ob_start();

if (isset($_POST['update_feature'])) {
    $feature_id = (int)$_POST['feature_id'];
    $title_en = $_POST['title_en'];
    $title_ar = $_POST['title_ar'];
    $description_en = $_POST['description_en'];
    $description_ar = $_POST['description_ar'];
    $icon_class = $_POST['icon_class'];

    $stmt = $dbcon->prepare("UPDATE features SET title_en=?, title_ar=?, description_en=?, description_ar=?, icon_class=? WHERE id=?");
    $stmt->bind_param("sssssi", $title_en, $title_ar, $description_en, $description_ar, $icon_class, $feature_id);
    $stmt->execute();
    $stmt->close();

    header('Location: manage_features.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_features.php');
    exit();
}
$id_to_edit = (int)$_GET['id'];

$stmt = $dbcon->prepare("SELECT * FROM features WHERE id = ?");
$stmt->bind_param("i", $id_to_edit);
$stmt->execute();
$result = $stmt->get_result();
$feature = $result->fetch_assoc();
$stmt->close();

if (!$feature) {
    header('Location: manage_features.php');
    exit();
}

$title = "Edit Feature";
require_once "../Dashboard/header.php"; 
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Feature: <?= htmlspecialchars($feature['title_en']) ?></h4>
    </div>
    <div class="card-body">
        <form action="feature_edit.php?id=<?= $id_to_edit ?>" method="post">
            <input type="hidden" name="feature_id" value="<?= $id_to_edit ?>">

            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Title (English)</label><input type="text" class="form-control" name="title_en" value="<?= htmlspecialchars($feature['title_en']) ?>" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Title (Arabic)</label><input type="text" class="form-control" name="title_ar" value="<?= htmlspecialchars($feature['title_ar']) ?>" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Description (English)</label><textarea name="description_en" class="form-control" rows="3" required><?= htmlspecialchars($feature['description_en']) ?></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>Description (Arabic)</label><textarea name="description_ar" class="form-control" rows="3" required><?= htmlspecialchars($feature['description_ar']) ?></textarea></div></div>
            </div>
            <div class="form-group">
                <label>Icon Class</label>
                <input type="text" class="form-control" name="icon_class" value="<?= htmlspecialchars($feature['icon_class']) ?>" placeholder="e.g., bx bx-code-alt" required>
                <small>Use icon class names from <a href="https://boxicons.com/" target="_blank">Boxicons</a>.</small>
            </div>
            
            <hr>
            <div class="text-right">
                 <a href="manage_features.php" class="btn btn-secondary">Cancel</a>
                 <button type="submit" name="update_feature" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>