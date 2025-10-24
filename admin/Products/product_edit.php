<?php

require_once "../UserSetting/user_auth.php"; 
require_once '../Database/db.php';
ob_start();

if (isset($_POST['update_product'])) {
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $details_url = $_POST['details_url'];
    $category_ids = $_POST['category_ids'] ?? []; 

    $stmt_img = $dbcon->prepare("SELECT image FROM products WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $current_image = $stmt_img->get_result()->fetch_assoc()['image'];
    $stmt_img->close();

    $new_image_name = $current_image;

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        if ($current_image != 'default.png') {
            $old_filepath = '../../assets/img/portfolio/' . $current_image;
            if (file_exists($old_filepath)) {
                unlink($old_filepath);
            }
        }
        $target_dir = "../../assets/img/portfolio/";
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = "product_" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_image_name);
    }

    $stmt_update = $dbcon->prepare("UPDATE products SET name = ?, image = ?, details_url = ? WHERE id = ?");
    $stmt_update->bind_param("sssi", $name, $new_image_name, $details_url, $id);
    $stmt_update->execute();
    $stmt_update->close();

    $stmt_delete_cats = $dbcon->prepare("DELETE FROM product_category_map WHERE product_id = ?");
    $stmt_delete_cats->bind_param("i", $id);
    $stmt_delete_cats->execute();
    $stmt_delete_cats->close();

    if (!empty($category_ids)) {
        $stmt_add_cats = $dbcon->prepare("INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)");
        foreach ($category_ids as $category_id) {
            $stmt_add_cats->bind_param("ii", $id, $category_id);
            $stmt_add_cats->execute();
        }
        $stmt_add_cats->close();
    }

    header('Location: manage_products.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_products.php');
    exit();
}
$id_to_edit = (int)$_GET['id'];

$stmt_prod = $dbcon->prepare("SELECT * FROM products WHERE id = ?");
$stmt_prod->bind_param("i", $id_to_edit);
$stmt_prod->execute();
$product = $stmt_prod->get_result()->fetch_assoc();
$stmt_prod->close();

if (!$product) {
    header('Location: manage_products.php');
    exit();
}

$all_categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");

$stmt_current_cats = $dbcon->prepare("SELECT category_id FROM product_category_map WHERE product_id = ?");
$stmt_current_cats->bind_param("i", $id_to_edit);
$stmt_current_cats->execute();
$current_cats_result = $stmt_current_cats->get_result();
$current_category_ids = [];
while ($row = $current_cats_result->fetch_assoc()) {
    $current_category_ids[] = $row['category_id'];
}
$stmt_current_cats->close();

$title = "Edit Product";
require_once "../Dashboard/header.php"; 
?>

<div class="card">
    <div class="card-header"><h4 class="card-title">Edit Product: <?= htmlspecialchars($product['name']) ?></h4></div>
    <div class="card-body">
        <form action="product_edit.php?id=<?= $id_to_edit ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $id_to_edit ?>">

            <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Product Categories (Hold Ctrl/Cmd to select multiple)</label>
                <select name="category_ids[]" class="form-control" required multiple size="5">
                    <?php while($cat = $all_categories_result->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>" <?= in_array($cat['id'], $current_category_ids) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <small>Manage categories <a href="manage_categories.php">here</a>.</small>
            </div>
            
            <div class="form-group">
                <label>Current Image</label>
                <div>
                    <img src="../../assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" alt="Current Image" style="width: 100px; margin-bottom: 10px;">
                </div>
                <label>Upload New Image (Optional)</label>
                <input type="file" class="form-control-file" name="image">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>
            
             <div class="form-group">
                <label>Details URL (Optional)</label>
                <input type="url" class="form-control" name="details_url" placeholder="https://example.com/product/123" value="<?= htmlspecialchars($product['details_url']) ?>">
            </div>
            <hr>
            <div class="text-right">
                <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_product" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require_once "../Dashboard/footer.php"; ?>