<?php
//  File: get_product_images.php

require_once '../admin/Database/db.php'; // تأكد من صحة المسار

// التحقق من وجود معرّف المنتج
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid Product ID']);
    exit;
}

$productId = (int)$_GET['id'];

// استخدام Prepared Statements للحماية من SQL Injection
$stmt = $dbcon->prepare("SELECT image_filename FROM product_images WHERE product_id = ? ORDER BY id ASC");
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row['image_filename'];
}

$stmt->close();
$dbcon->close();

// إرجاع البيانات بصيغة JSON
header('Content-Type: application/json');
echo json_encode($images);
?>