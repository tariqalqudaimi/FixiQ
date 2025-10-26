<?php
// يتضمن ملفات المصادقة والاتصال بقاعدة البيانات
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';
ob_start(); // لمنع أخطاء "headers already sent" عند إعادة التوجيه

// --- القسم الأول: معالجة البيانات عند إرسال الفورم (Update) ---
if (isset($_POST['update_product'])) {
    // 1. جلب البيانات الأساسية من الفورم
    $id = (int)$_POST['id'];
    $name = $_POST['name'];
    $name_ar = $_POST['name_ar'];
    $description = $_POST['description'];
    $description_ar = $_POST['description_ar'];
    $details_url = $_POST['details_url'];
    $category_ids = $_POST['category_ids'] ?? [];

    // 2. التعامل مع الصورة الرئيسية (إذا تم رفع صورة جديدة)
    $stmt_img = $dbcon->prepare("SELECT image FROM products WHERE id = ?");
    $stmt_img->bind_param("i", $id);
    $stmt_img->execute();
    $current_image = $stmt_img->get_result()->fetch_assoc()['image'];
    $stmt_img->close();

    $new_image_name = $current_image; // القيمة الافتراضية هي الصورة الحالية
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // حذف الصورة القديمة إذا لم تكن الصورة الافتراضية
        if ($current_image != 'default.png' && file_exists('../../assets/img/portfolio/' . $current_image)) {
            unlink('../../assets/img/portfolio/' . $current_image);
        }
        $target_dir = "../../assets/img/portfolio/";
        $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = "product_" . time() . "." . $file_ext;
        move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $new_image_name);
    }

    // 3. تحديث البيانات الأساسية للمنتج في قاعدة البيانات
    $stmt_update = $dbcon->prepare("UPDATE products SET name = ?, name_ar = ?, image = ?, details_url = ?, description = ?, description_ar = ? WHERE id = ?");
    $stmt_update->bind_param("ssssssi", $name, $name_ar, $new_image_name, $details_url, $description, $description_ar, $id);
    $stmt_update->execute();
    $stmt_update->close();

    // 4. تحديث الفئات (Categories)
    // أولاً: حذف كل الفئات الحالية المرتبطة بالمنتج
    $stmt_delete_cats = $dbcon->prepare("DELETE FROM product_category_map WHERE product_id = ?");
    $stmt_delete_cats->bind_param("i", $id);
    $stmt_delete_cats->execute();
    $stmt_delete_cats->close();
    // ثانياً: إضافة الفئات الجديدة التي تم اختيارها
    if (!empty($category_ids)) {
        $stmt_add_cats = $dbcon->prepare("INSERT INTO product_category_map (product_id, category_id) VALUES (?, ?)");
        foreach ($category_ids as $category_id) {
            $stmt_add_cats->bind_param("ii", $id, $category_id);
            $stmt_add_cats->execute();
        }
        $stmt_add_cats->close();
    }

    // 5. إدارة الصور الإضافية
    // أولاً: حذف الصور التي تم تحديدها للحذف
    if (!empty($_POST['delete_images'])) {
        $stmt_get_filename = $dbcon->prepare("SELECT image_filename FROM product_images WHERE id = ?");
        $stmt_delete_img = $dbcon->prepare("DELETE FROM product_images WHERE id = ?");
        foreach ($_POST['delete_images'] as $img_id_to_delete) {
            // جلب اسم الملف للحذف من السيرفر
            $stmt_get_filename->bind_param("i", $img_id_to_delete);
            $stmt_get_filename->execute();
            if($img_result = $stmt_get_filename->get_result()->fetch_assoc()) {
                $img_file = $img_result['image_filename'];
                if (file_exists('../../assets/img/portfolio/' . $img_file)) {
                    unlink('../../assets/img/portfolio/' . $img_file);
                }
            }
            // حذف السجل من قاعدة البيانات
            $stmt_delete_img->bind_param("i", $img_id_to_delete);
            $stmt_delete_img->execute();
        }
        $stmt_get_filename->close();
        $stmt_delete_img->close();
    }
    // ثانياً: إضافة الصور الجديدة التي تم رفعها
    if (isset($_FILES['additional_images'])) {
        $additional_images = $_FILES['additional_images'];
        $stmt_add_img = $dbcon->prepare("INSERT INTO product_images (product_id, image_filename) VALUES (?, ?)");
        for ($i = 0; $i < count($additional_images['name']); $i++) {
            if ($additional_images['error'][$i] == 0) {
                $target_dir = "../../assets/img/portfolio/";
                $ext = pathinfo($additional_images['name'][$i], PATHINFO_EXTENSION);
                $image_name = "prod_add_" . $id . "_" . time() . "_" . $i . "." . $ext;
                if (move_uploaded_file($additional_images['tmp_name'][$i], $target_dir . $image_name)) {
                    $stmt_add_img->bind_param("is", $id, $image_name);
                    $stmt_add_img->execute();
                }
            }
        }
        $stmt_add_img->close();
    }

    // 6. إعادة التوجيه إلى صفحة إدارة المنتجات
    header('Location: manage_products.php');
    exit();
}

// --- القسم الثاني: جلب البيانات لعرضها في الفورم ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_products.php');
    exit();
}
$id_to_edit = (int)$_GET['id'];

// جلب بيانات المنتج الأساسية
$stmt_prod = $dbcon->prepare("SELECT * FROM products WHERE id = ?");
$stmt_prod->bind_param("i", $id_to_edit);
$stmt_prod->execute();
$product_result = $stmt_prod->get_result();
$product = $product_result->fetch_assoc();
$stmt_prod->close();
if (!$product) {
    header('Location: manage_products.php');
    exit();
}

// جلب الصور الإضافية الحالية
$stmt_add_imgs = $dbcon->prepare("SELECT id, image_filename FROM product_images WHERE product_id = ?");
$stmt_add_imgs->bind_param("i", $id_to_edit);
$stmt_add_imgs->execute();
$additional_images_result = $stmt_add_imgs->get_result();
$stmt_add_imgs->close();

// جلب كل الفئات المتاحة
$all_categories_result = $dbcon->query("SELECT * FROM product_categories ORDER BY name ASC");

// جلب الفئات المرتبطة حالياً بالمنتج
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

<!-- --- القسم الثالث: عرض الفورم HTML --- -->
<div class="card">
    <div class="card-header"><h4 class="card-title">Edit Product: <?= htmlspecialchars($product['name']) ?></h4></div>
    <div class="card-body">
        <form action="product_edit.php?id=<?= $id_to_edit ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $id_to_edit ?>">

            <div class="form-group">
                <label>Product Name (English)</label>
                <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Product Name (Arabic)</label>
                <input type="text" class="form-control" name="name_ar" value="<?= htmlspecialchars($product['name_ar'] ?? '') ?>" dir="rtl">
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
            </div>
            
            <div class="form-group">
                <label>Description (English)</label>
                <textarea class="form-control" name="description" rows="3"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Description (Arabic)</label>
                <textarea class="form-control" name="description_ar" rows="3" dir="rtl"><?= htmlspecialchars($product['description_ar'] ?? '') ?></textarea>
            </div>
            
            <hr>
            
            <div class="form-group">
                <label>Current Main Image</label>
                <div><img src="../../assets/img/portfolio/<?= htmlspecialchars($product['image']) ?>" alt="Current Image" style="width: 100px; margin-bottom: 10px;"></div>
                <label>Upload New Main Image (Optional)</label>
                <input type="file" class="form-control-file" name="image">
                <small class="form-text text-muted">Leave blank to keep the current image.</small>
            </div>
            
            <hr>

            <h5>Manage Additional Images</h5>
            <div class="form-group">
                <label>Current Additional Images</label>
                <div style="display: flex; flex-wrap: wrap; gap: 15px; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <?php if ($additional_images_result->num_rows > 0): ?>
                        <?php while($img = $additional_images_result->fetch_assoc()): ?>
                            <div style="text-align: center; position: relative;">
                                <img src="../../assets/img/portfolio/<?= htmlspecialchars($img['image_filename']) ?>" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                <br>
                                <label style="cursor: pointer; font-size: 0.9em;">
                                    <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>"> Delete
                                </label>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No additional images found.</p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label>Upload New Additional Images (Optional)</label>
                <input type="file" class="form-control-file" name="additional_images[]" multiple>
            </div>

            <hr>

             <div class="form-group">
                <label>Details URL (Optional)</label>
                <input type="url" class="form-control" name="details_url" placeholder="https://example.com/product/123" value="<?= htmlspecialchars($product['details_url'] ?? '') ?>">
            </div>
            
            <div class="text-right mt-4">
                <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" name="update_product" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require_once "../Dashboard/footer.php"; ?>```
