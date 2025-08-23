<?php
// STEP 1: All includes and form processing logic goes at the very top.
require_once "user_auth.php";
require_once "db.php";

// Handle Add Product form submission BEFORE any HTML is sent.
if(isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $details_url = $_POST['details_url'];

    // Handle image upload
    $image_name = 'default.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../assets/img/portfolio/";
        if(!is_dir($target_dir)){ mkdir($target_dir, 0755, true); }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "product_".time().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    $stmt = $dbcon->prepare("INSERT INTO products (name, category_id, image, details_url) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $category_id, $image_name, $details_url);
    $stmt->execute();

    // This will now work because no HTML has been sent yet.
    header('Location: manage_products.php');
    exit(); // Always use exit() after a header redirect.
}

// Fetch categories for the dropdown (this is safe to do here)
$categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");

// Fetch all products to display on the page
$products_result = $dbcon->query("SELECT p.*, c.name as category_name FROM products p JOIN product_categories c ON p.category_id = c.id ORDER BY p.id DESC");


// STEP 2: Now that all header-related logic is done, we can start the HTML page.
$title = "Manage Products";
require_once "header.php";
?>

<!-- STEP 3: The rest of the file is just the HTML for displaying the page. -->

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Product</h4></div>
    <div class="card-body">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="form-group">
                <label>Product Category</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Select Category --</option>
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
            <thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($products_result as $product) : ?>
                    <tr>
                        <td><img src="../assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 80px;"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                        <td><a href="product_delete.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sure?');">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>