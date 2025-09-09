<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';

if (isset($_POST['add_feature'])) {
    $title_en = $_POST['title_en'];
    $title_ar = $_POST['title_ar'];
    $description_en = $_POST['description_en'];
    $description_ar = $_POST['description_ar'];
    $icon_class = $_POST['icon_class'];
    
    $stmt = $dbcon->prepare("INSERT INTO features (title_en, title_ar, description_en, description_ar, icon_class) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title_en, $title_ar, $description_en, $description_ar, $icon_class);
    $stmt->execute();

    header('Location: manage_features.php');
    exit();
}


$features_result = $dbcon->query("SELECT * FROM features ORDER BY display_order ASC, id DESC");

$title = "Manage Features";
require_once "../Dashboard/header.php";
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Feature</h4></div>
    <div class="card-body">
        <form action="manage_features.php" method="post">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Title (English)</label><input type="text" class="form-control" name="title_en" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Title (Arabic)</label><input type="text" class="form-control" name="title_ar" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Description (English)</label><textarea name="description_en" class="form-control" rows="3" required></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>Description (Arabic)</label><textarea name="description_ar" class="form-control" rows="3" required></textarea></div></div>
            </div>
            <div class="form-group"><label>Icon Class</label><input type="text" class="form-control" name="icon_class" placeholder="e.g., bx bx-code-alt" required><small>Use icon class names from <a href="https://boxicons.com/" target="_blank">Boxicons</a>.</small></div>
            <button type="submit" name="add_feature" class="btn btn-primary">Add Feature</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Features</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Icon</th><th>Title (English)</th><th>Description (English)</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($features_result as $feature) : ?>
                    <tr>
                        <td><i class="<?= htmlspecialchars($feature['icon_class']) ?>" style="font-size: 24px;"></i></td>
                        <td><?= htmlspecialchars($feature['title_en']) ?></td>
                        <td><?= htmlspecialchars(substr($feature['description_en'], 0, 100)) ?>...</td>
                        <td>
                            <a href="feature_edit.php?id=<?= $feature['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                            
                            <a href="feature_delete.php?id=<?= $feature['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php require_once "../Dashboard/footer.php"; ?>