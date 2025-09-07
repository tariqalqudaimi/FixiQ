<?php
require_once "user_auth.php";
require_once "db.php";

if (isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $title_ar = $_POST['title_ar'];
    $description = $_POST['description'];
    $description_ar = $_POST['description_ar'];
    $box_color_class = $_POST['box_color_class'];
    $image_file_name = 'default.png';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/services/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_file_name = "service-" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_file_name);
    }

    $stmt = $dbcon->prepare("INSERT INTO services (title, title_ar, description, description_ar, image_file, box_color_class) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $title_ar, $description, $description_ar, $image_file_name, $box_color_class);
    $stmt->execute();

    header('Location: manage_services.php');
    exit();
}

if (isset($_POST['update_service'])) {
    $id = (int)$_POST['id'];
    $title = $_POST['title'];
    $title_ar = $_POST['title_ar'];
    $description = $_POST['description'];
    $description_ar = $_POST['description_ar'];
    $box_color_class = $_POST['box_color_class'];

    $stmt_img = $dbcon->prepare("SELECT image_file FROM services WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $current_image = $stmt_img->get_result()->fetch_assoc()['image_file'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        if ($current_image != 'default.png') {
            $old_filepath = '../assets/img/services/' . $current_image;
            if (file_exists($old_filepath)) unlink($old_filepath);
        }
        $target_dir = "../assets/img/services/";
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = "service-" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_image_name);
    } else {
        $new_image_name = $current_image;
    }

    $stmt_update = $dbcon->prepare("UPDATE services SET title = ?, title_ar = ?, description = ?, description_ar = ?, image_file = ?, box_color_class = ? WHERE id = ?");
    $stmt_update->bind_param("ssssssi", $title, $title_ar, $description, $description_ar, $new_image_name, $box_color_class, $id);
    $stmt_update->execute();

    header('Location: manage_services.php');
    exit();
}

if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    $stmt_select = $dbcon->prepare("SELECT image_file FROM services WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result()->fetch_assoc();
    if ($result && $result['image_file'] != 'default.png') {
        $filepath = '../assets/img/services/' . $result['image_file'];
        if (file_exists($filepath)) unlink($filepath);
    }

    $stmt_delete = $dbcon->prepare("DELETE FROM services WHERE id=?");
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();

    header('Location: manage_services.php');
    exit();
}

$services_result = $dbcon->query("SELECT * FROM services ORDER BY id DESC");

$title = "Manage Services";
require_once "header.php";
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
                        <td><img src="../assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="" style="width: 40px; height: 40px; background: #eee; padding: 5px; border-radius: 5px;"></td>
                        <td><?= htmlspecialchars($service['title']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info edit-btn" 
                                    data-toggle="modal" 
                                    data-target="#editServiceModal"
                                    data-id="<?= $service['id'] ?>"
                                    data-title="<?= htmlspecialchars($service['title']) ?>"
                                    data-title-ar="<?= htmlspecialchars($service['title_ar']) ?>"
                                    data-description="<?= htmlspecialchars($service['description']) ?>"
                                    data-description-ar="<?= htmlspecialchars($service['description_ar']) ?>"
                                    data-color="<?= htmlspecialchars($service['box_color_class']) ?>"
                                    data-image="../assets/img/services/<?= htmlspecialchars($service['image_file']) ?>">
                                Edit
                            </button>
                            <a href="manage_services.php?delete_id=<?= $service['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="manage_services.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Title (English)</label><input type="text" class="form-control" name="title" id="edit-title" required></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Title (Arabic)</label><input type="text" class="form-control" name="title_ar" id="edit-title-ar" required></div></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>Description (English)</label><textarea class="form-control" name="description" id="edit-description" rows="3" required></textarea></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Description (Arabic)</label><textarea class="form-control" name="description_ar" id="edit-description-ar" rows="3" required></textarea></div></div>
                    </div>
                    <div class="form-group">
                        <label>Icon/Image</label>
                        <input type="file" class="form-control-file" name="image">
                        <small class="form-text text-muted">Current: <img id="edit-current-image" src="" alt="Current Image" style="width: 25px; height: 25px;"> - Leave blank to keep current image.</small>
                    </div>
                    <div class="form-group">
                        <label>Box Color</label>
                        <select name="box_color_class" id="edit-color" class="form-control">
                            <option value="iconbox-blue">Blue</option><option value="iconbox-orange">Orange</option><option value="iconbox-green">Green</option><option value="iconbox-pink">Pink</option><option value="iconbox-yellow">Yellow</option><option value="iconbox-red">Red</option><option value="iconbox-teal">Teal</option><option value="iconbox-purple">Purple</option>
                        </select>
                    </div>
                    <hr>
                    <div class="text-right">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                         <button type="submit" name="update_service" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once "footer.php";
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    
    $('#editServiceModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 

        var id = button.data('id');
        var title = button.data('title');
        var titleAr = button.data('title-ar');
        var description = button.data('description');
        var descriptionAr = button.data('description-ar');
        var color = button.data('color');
        var imageUrl = button.data('image');

        var modal = $(this);
        modal.find('#edit-id').val(id);
        modal.find('#edit-title').val(title);
        modal.find('#edit-title-ar').val(titleAr);
        modal.find('#edit-description').val(description);
        modal.find('#edit-description-ar').val(descriptionAr);
        modal.find('#edit-color').val(color);
        modal.find('#edit-current-image').attr('src', imageUrl);
    });
});
</script>