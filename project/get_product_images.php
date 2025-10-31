<?php

//  File: get_product_images.php
require_once '../admin/Database/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400); 
    echo json_encode(['error' => 'Invalid Product ID']);
    exit;
}

$productId = (int)$_GET['id'];

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

header('Content-Type: application/json');
echo json_encode($images);
?>