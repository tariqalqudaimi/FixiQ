<?php
// STEP 1: All includes and form processing logic goes at the very top.
require_once "user_auth.php";
require_once "db.php";

// Handle Add Product form submission BEFORE any HTML is sent.
if(isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $details_url = $_POST['details_url'];
    // CHANGED: We now receive an array of category IDs
    $category_ids = $_POST['category_ids']; // This will be an array

    // Handle image upload
    $image_name = 'default.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../assets/img/portfolio/";
        if(!is_dir($target_dir)){ mkdir($target_dir, 0755, true); }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = "product_".time().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $image_name);
    }

    // NEW LOGIC: Step 1 - Insert into products table (without category_id)
    $stmt = $dbcon->prepare("INSERT INTO products (name, image, details_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $image_name, $details_url);
    $stmt->execute();
    
    // NEW LOGIC: Step 2 - Get the ID of the product we just created
    $new_product_id = $dbcon->insert_id;

    // NEW LOGIC: Step 3 - Loop through selected categories and insert into the map table
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

// Fetch categories for the dropdown
$categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");

// CHANGED: Updated query to fetch multiple category names using GROUP_CONCAT
$products_result = $dbcon->query("
    SELECT 
        p.*, 
        GROUP_CONCAT(c.name SEPARATOR ', ') as category_names
    FROM 
        products p
    LEFT JOIN 
        product_category_map pcm ON p.id = pcm.product_id
    LEFT JOIN 
        product_categories c ON pcm.category_id = c.id
    GROUP BY 
        p.id
    ORDER BY 
        p.id DESC
");

// STEP 2: Start the HTML page.
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
                <label>Product Categories (Hold Ctrl/Cmd to select multiple)</label>
                <!-- CHANGED: select is now 'multiple' and name is an array 'category_ids[]' -->
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
                        <td><img src="../assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 80px;"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <!-- CHANGED: Displaying the concatenated category names -->
                        <td><?= htmlspecialchars($product['category_names'] ?? 'N/A') ?></td>
                        <td>
                            <!-- Add an Edit button here later if needed -->
                            <a href="product_delete.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once "footer.php"; ?>