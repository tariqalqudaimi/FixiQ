<?php
require_once "user_auth.php";
require_once "db.php";

if(isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Sanitize the ID

    // Optional but good practice: Delete the image file from the server
    $stmt_img = $dbcon->prepare("SELECT image FROM products WHERE id = ?");
    $stmt_img->bind_param("i", $product_id);
    $stmt_img->execute();
    $result = $stmt_img->get_result();
    if($row = $result->fetch_assoc()) {
        $image_to_delete = $row['image'];
        $file_path = "../assets/img/portfolio/" . $image_to_delete;
        if(file_exists($file_path) && $image_to_delete !== 'default.png') {
            unlink($file_path);
        }
    }
    $stmt_img->close();

    // NEW: Delete associations from the map table first
    $stmt_map = $dbcon->prepare("DELETE FROM product_category_map WHERE product_id = ?");
    $stmt_map->bind_param("i", $product_id);
    $stmt_map->execute();
    $stmt_map->close();

    // Finally, delete the product itself
    $stmt_prod = $dbcon->prepare("DELETE FROM products WHERE id = ?");
    $stmt_prod->bind_param("i", $product_id);
    $stmt_prod->execute();
    $stmt_prod->close();
}

header('Location: manage_products.php');
exit();
?>