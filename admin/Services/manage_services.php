<?php
// manage_services.php (Updated)

require_once "../UserSetting/user_auth.php"; 
require_once '../Database/db.php';

// --- (Add and Delete logic remains the same) ---
if (isset($_POST['add_service'])) {
    // ... (Your existing add logic is correct and stays here)
}
if (isset($_GET['delete_id'])) {
    // ... (Your existing delete logic is correct and stays here)
}
// --- (The UPDATE logic has been moved to services_edit.php) ---


$services_result = $dbcon->query("SELECT * FROM services ORDER BY id DESC");
$title = "Manage Services";
require_once "../Dashboard/header.php"; 
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Service</h4></div>
    <div class="card-body">
        <form action="manage_services.php" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Title (English)</label><input type="text" class="form-control" name="title" required></div></div>
                <div class="col-md-6"><div class="form-group"><label>Title (Arabic)</label><input type="text" class="form-control" name="title_ar" required></div></div>
            </div>
            <div class="row">
                <div class="col-md-6"><div class="form-group"><label>Description (English)</label><textarea class="form-control" name="description" rows="3" required></textarea></div></div>
                <div class="col-md-6"><div class="form-group"><label>Description (Arabic)</label><textarea class="form-control" name="description_ar" rows="3" required></textarea></div></div>
            </div>
            <div class="form-group"><label>Icon/Image</label><input type="file" class="form-control-file" name="image" required></div>
            <div class="form-group"><label>Box Color</label><select name="box_color_class" class="form-control"><option value="iconbox-blue">Blue</option><option value="iconbox-orange">Orange</option><option value="iconbox-green">Green</option><option value="iconbox-pink">Pink</option><option value="iconbox-yellow">Yellow</option><option value="iconbox-red">Red</option><option value="iconbox-teal">Teal</option><option value="iconbox-purple">Purple</option></select></div>
            <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Services</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($services_result as $service) : ?>
                    <tr>
                        <td><img src="../../assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="" style="width: 40px; height: 40px; background: #eee; padding: 5px; border-radius: 5px;"></td>
                        <td><?= htmlspecialchars($service['title']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td>
                            <a href="services_edit.php?id=<?= $service['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                            
                            <a href="manage_services.php?delete_id=<?= $service['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once "../Dashboard/footer.php";
?>