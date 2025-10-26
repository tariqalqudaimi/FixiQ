<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';
$title = "Manage Products";

if(isset($_POST['add_product'])) {
    // جلب كل البيانات من الفورم
    $name = $_POST['name'];
    $name_ar = $_POST['name_ar']; // الجديد
    $description = $_POST['description']; // الجديد
    $description_ar = $_POST['description_ar']; // الجديد
    $details_url = $_POST['details_url'];
    $category_ids = $_POST['category_ids'] ?? [];

    // التعامل مع الصورة الرئيسية (لا تغيير هنا)
    $main_image_name = 'default.png';
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../../assets/img/portfolio/";
        if(!is_dir($target_dir)){ mkdir($target_dir, 0755, true); }
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $main_image_name = "product_".time().".".$ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $main_image_name);
    }

    // تعديل استعلام الإدخال ليشمل الحقول الجديدة
    $stmt = $dbcon->prepare("INSERT INTO products (name, name_ar, image, details_url, description, description_ar) VALUES (?, ?, ?, ?, ?, ?)");
    // تعديل bind_param ليتوافق مع الحقول الجديدة (s s s s s s)
    $stmt->bind_param("ssssss", $name, $name_ar, $main_image_name, $details_url, $description, $description_ar);
    $stmt->execute();
    $new_product_id = $dbcon->insert_id;
    $stmt->close();

    // ربط الفئات والصور الإضافية (لا تغيير هنا)
    if(!empty($category_ids) && $new_product_id) { /* ... code ... */ }
    if(isset($_FILES['additional_images']) && $new_product_id) { /* ... code ... */ }
    
    header('Location: manage_products.php');
    exit();
}

// تعديل استعلام العرض ليشمل الاسم العربي
$products_result = $dbcon->query("
    SELECT
        p.id, p.name, p.name_ar, p.image, -- أضفنا name_ar هنا
        GROUP_CONCAT(c.name SEPARATOR ', ') as category_names,
        (SELECT COUNT(id) FROM product_images WHERE product_id = p.id) as additional_images_count
    FROM products p
    LEFT JOIN product_category_map pcm ON p.id = pcm.product_id
    LEFT JOIN product_categories c ON pcm.category_id = c.id
    GROUP BY p.id ORDER BY p.id DESC
");

$categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");
require_once "../Dashboard/header.php";
?>

<div class="card mb-4">
    <div class="card-header"><h4 class="card-title">Add New Product</h4></div>
    <div class="card-body">
        <form action="manage_products.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Product Name (English)</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <!-- حقل الاسم العربي -->
            <div class="form-group">
                <label>Product Name (Arabic)</label>
                <input type="text" class="form-control" name="name_ar" dir="rtl">
            </div>
            <div class="form-group">
                <label>Product Categories</label>
                <select name="category_ids[]" class="form-control" required multiple size="5">
                    <?php mysqli_data_seek($categories_result, 0); ?>
                    <?php while($cat = $categories_result->fetch_assoc()): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- حقل الوصف الإنجليزي -->
            <div class="form-group">
                <label>Description (English)</label>
                <textarea class="form-control" name="description" rows="3"></textarea>
            </div>
             <!-- حقل الوصف العربي -->
            <div class="form-group">
                <label>Description (Arabic)</label>
                <textarea class="form-control" name="description_ar" rows="3" dir="rtl"></textarea>
            </div>
            <div class="form-group">
                <label>Main Product Image</label>
                <input type="file" class="form-control-file" name="image" required>
            </div>
            <div class="form-group">
                <label>Additional Images</label>
                <input type="file" class="form-control-file" name="additional_images[]" multiple>
            </div>
             <div class="form-group">
                <label>Details URL (Optional)</label>
                <input type="url" class="form-control" name="details_url" placeholder="https://example.com">
            </div>
            <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h4 class="card-title">Existing Products</h4></div>
    <div class="card-body">
        <table class="table table-bordered">
            <!-- إضافة عمود للاسم العربي -->
            <thead><tr><th>Image</th><th>Name (EN)</th><th>Name (AR)</th><th>Categories</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($products_result as $product) : ?>
                    <tr>
                        <td><img src="../../assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" alt="" style="width: 80px;"></td>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                        <!-- عرض الاسم العربي -->
                        <td><?= htmlspecialchars($product['name_ar'] ?? '') ?></td>
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