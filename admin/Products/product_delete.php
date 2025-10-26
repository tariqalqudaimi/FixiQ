<?php
require_once "../UserSetting/user_auth.php";
require_once '../Database/db.php';

if(isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    // 1. (الجديد) حذف ملفات الصور الإضافية من السيرفر
    $stmt_add_imgs = $dbcon->prepare("SELECT image_filename FROM product_images WHERE product_id = ?");
    $stmt_add_imgs->bind_param("i", $product_id);
    $stmt_add_imgs->execute();
    $additional_images = $stmt_add_imgs->get_result();
    while ($img = $additional_images->fetch_assoc()) {
        $file_path = "../../assets/img/portfolio/" . $img['image_filename'];
        if(file_exists($file_path)) {
            unlink($file_path);
        }
    }
    $stmt_add_imgs->close();

    // 2. حذف الصورة الرئيسية من السيرفر
    $stmt_main_img = $dbcon->prepare("SELECT image FROM products WHERE id = ?");
    $stmt_main_img->bind_param("i", $product_id);
    $stmt_main_img->execute();
    $result = $stmt_main_img->get_result();
    if($row = $result->fetch_assoc()) {
        if ($row['image'] !== 'default.png') {
            $file_path = "../../assets/img/portfolio/" . $row['image'];
            if(file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    $stmt_main_img->close();

    // 3. حذف المنتج من قاعدة البيانات (وهذا سيحذف تلقائياً السجلات المرتبطة بفضل ON DELETE CASCADE)
    $stmt_prod = $dbcon->prepare("DELETE FROM products WHERE id = ?");
    $stmt_prod->bind_param("i", $product_id);
    $stmt_prod->execute();
    $stmt_prod->close();
}

header('Location: manage_products.php');
exit();
?>