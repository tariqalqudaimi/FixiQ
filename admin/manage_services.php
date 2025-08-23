<?php
// STEP 1: All PHP logic goes first.
require_once "user_auth.php";
require_once "db.php";

// Handle Add Service form submission.
if (isset($_POST['add_service'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $box_color_class = $_POST['box_color_class'];
    $image_file_name = 'default.png'; // Default image

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/services/"; // Path for frontend images
        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_file_name = "service-" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_file_name);
    }

    $stmt = $dbcon->prepare("INSERT INTO services (title, description, image_file, box_color_class) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $description, $image_file_name, $box_color_class);
    $stmt->execute();

    header('Location: manage_services.php');
    exit();
}

// Handle Delete request.
if (isset($_GET['delete_id'])) {
    $id = (int)$_GET['delete_id'];
    
    // First, get the filename to delete the image from the server
    $stmt_select = $dbcon->prepare("SELECT image_file FROM services WHERE id = ?");
    $stmt_select->bind_param("i", $id);
    $stmt_select->execute();
    $result = $stmt_select->get_result()->fetch_assoc();
    if ($result && $result['image_file'] != 'default.png') {
        $filepath = '../assets/img/services/' . $result['image_file'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    // Now, delete the record from the database
    $stmt_delete = $dbcon->prepare("DELETE FROM services WHERE id=?");
    $stmt_delete->bind_param("i", $id);
    $stmt_delete->execute();

    header('Location: manage_services.php');
    exit();
}

// Fetch all services from the database to display on the page.
$services_result = $dbcon->query("SELECT * FROM services ORDER BY id DESC");

// STEP 2: Start the HTML page.
$title = "Manage Services";
require_once "header.php";
?>

<!-- STEP 3: HTML content. -->
<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Service</h4></div>
    <div class="card-body">
        <form action="manage_services.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>Icon/Image (PNG, SVG, JPG)</label>
                <input type="file" class="form-control-file" name="image" required>
            </div>
            <div class="form-group">
                <label>Box Color</label>
                <select name="box_color_class" class="form-control">
                    <option value="iconbox-blue">Blue</option>
                    <option value="iconbox-orange">Orange</option>
                    <option value="iconbox-green">Green</option>
                    <option value="iconbox-pink">Pink</option>
                    <option value="iconbox-yellow">Yellow</option>
                    <option value="iconbox-red">Red</option>
                    <option value="iconbox-teal">Teal</option>
                    <option value="iconbox-purple">Purple</option>
                </select>
            </div>
            <button type="submit" name="add_service" class="btn btn-primary">Add Service</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Services</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Icon</th><th>Title</th><th>Description</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($services_result as $service) : ?>
                    <tr>
                        <td><img src="../assets/img/services/<?= htmlspecialchars($service['image_file']) ?>" alt="" style="width: 40px; height: 40px; background: #eee; padding: 5px; border-radius: 5px;"></td>
                        <td><?= htmlspecialchars($service['title']) ?></td>
                        <td><?= htmlspecialchars($service['description']) ?></td>
                        <td><a href="manage_services.php?delete_id=<?= $service['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once "footer.php";
?>