<?php

require_once __DIR__ . '/../admin/Database/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($dbcon) || $dbcon->connect_error) {
        http_response_code(500);
        die("Error: Database connection failed.");
    }

    $name = strip_tags(trim($_POST["name"] ?? ''));
    $email = filter_var(trim($_POST["email"] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"] ?? ''));
    $message = trim($_POST["message"] ?? '');

    if (empty($name) || empty($subject) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        die("Please complete all fields with valid data.");
    }

    try {
        $stmt = $dbcon->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        http_response_code(500);
        die("Database Error: " . $e->getMessage());
    }
    
    http_response_code(200);
    exit();

} else {
    http_response_code(403);
    die("This page cannot be accessed directly.");
}
?>