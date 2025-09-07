<?php
require_once "user_auth.php";
require_once "db.php";

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

if (isset($_POST['edit_feature'])) {
    $feature_id = $_POST['feature_id'];
    $title_en = $_POST['title_en'];
    $title_ar = $_POST['title_ar'];
    $description_en = $_POST['description_en'];
    $description_ar = $_POST['description_ar'];
    $icon_class = $_POST['icon_class'];

    $stmt = $dbcon->prepare("UPDATE features SET title_en=?, title_ar=?, description_en=?, description_ar=?, icon_class=? WHERE id=?");
    $stmt->bind_param("sssssi", $title_en, $title_ar, $description_en, $description_ar, $icon_class, $feature_id);
    $stmt->execute();

    header('Location: manage_features.php');
    exit();
}

$features_result = $dbcon->query("SELECT * FROM features ORDER BY display_order ASC, id DESC");

$title = "Manage Features";
require_once "header.php";
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Feature</h4></div>
    <div class="card-body">
        <form action="" method="post">
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
                            <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editFeatureModal-<?= $feature['id'] ?>">
                                Edit
                            </button>
                            <a href="feature_delete.php?id=<?= $feature['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>

                    <div class="modal fade" id="editFeatureModal-<?= $feature['id'] ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Feature: <?= htmlspecialchars($feature['title_en']) ?></h5>
                                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <form action="" method="post">
                                        <input type="hidden" name="feature_id" value="<?= $feature['id'] ?>">
                                        <div class="row">
                                            <div class="col-md-6"><div class="form-group"><label>Title (English)</label><input type="text" class="form-control" name="title_en" value="<?= htmlspecialchars($feature['title_en']) ?>" required></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Title (Arabic)</label><input type="text" class="form-control" name="title_ar" value="<?= htmlspecialchars($feature['title_ar']) ?>" required></div></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"><div class="form-group"><label>Description (English)</label><textarea name="description_en" class="form-control" rows="3" required><?= htmlspecialchars($feature['description_en']) ?></textarea></div></div>
                                            <div class="col-md-6"><div class="form-group"><label>Description (Arabic)</label><textarea name="description_ar" class="form-control" rows="3" required><?= htmlspecialchars($feature['description_ar']) ?></textarea></div></div>
                                        </div>
                                        <div class="form-group"><label>Icon Class</label><input type="text" class="form-control" name="icon_class" value="<?= htmlspecialchars($feature['icon_class']) ?>" required></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" name="edit_feature" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>