<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';
$title = "Manage Products";

if(isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $details_url = $_POST['details_url'];
    $category_ids = $_POST['category_ids']; 

    $image_name = 'default.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../../assets/img/portfolio/"; 
        if(!is_dir($target_dir)){ mkdir($target_dir, 0755, true); }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "product_".time().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    $stmt = $dbcon->prepare("INSERT INTO products (name, image, details_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $image_name, $details_url);
    $stmt->execute();
    
    $new_product_id = $dbcon->insert_id;

    if(!empty($category_ids) && $new_product_id) {
        $map_stmt = $dbcon->prepare("INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)");
        foreach($category_ids as $category_id) {
            $map_stmt->bind_param("ii", $new_product_id, $category_id);
            $map_stmt->execute();
        }
    }

    header('Location: manage_products.php');
    exit();
}

$categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");
$products_result = $dbcon->query("
    SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as category_names
    FROM products p
    LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
    LEFT JOIN product_categories c ON pcm.category_id = c.id
    GROUP BY p.id ORDER BY p.id DESC
");

require_once "../Dashboard/header.php";
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Product</h4></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label>Product Categories (Hold Ctrl/Cmd to select multiple)</label>
                <select name="category_ids[]" class="form-control" required multiple size="5">
                    <?php while($cat = $categories_result->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
                <small>Manage categories <a href="manage_categories.php">here</a>.</small>
            </div>
            <div class="form-group">
                <label>Product Image</label>
                <input type="file" class="form-control-file" name="image" required>
            </div>
             <div class="form-group">
                <label>Details URL (Optional)</label>
                <input type="url" class="form-control" name="details_url" placeholder="https://example.com/product/123">
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Products</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead><tr><th>Image</th><th>Name</th><th>Categories</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($products_result as $product) : ?>
                    <tr>
                        <td><img src="../../assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 80px;"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category_names'] ?? 'N/A') ?></td>
                        <td>
                            <a href="product_edit.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                            <a href="product_delete.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "../Dashboard/footer.php"; ?>