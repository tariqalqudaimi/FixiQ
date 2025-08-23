<?php
// This script now saves messages to the database.

// We need the database connection.
require_once '../admin/db.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize form data to prevent XSS attacks
    $name = strip_tags(trim($_POST["name"]));
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = strip_tags(trim($_POST["subject"]));
    $message = strip_tags(trim($_POST["message"]));

    // Basic validation
    if (empty($name) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($subject) || empty($message)) {
        http_response_code(400); // Bad Request
        echo "Please complete the form and try again.";
        exit;
    }

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $dbcon->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // The message was saved successfully.
        // Send "OK" to the frontend JavaScript to show the success message.
        echo "OK";
    } else {
        // If the database insert fails, send a server error.
        http_response_code(500); // Internal Server Error
        echo "Oops! Something went wrong on our end.";
    }

    $stmt->close();
    $dbcon->close();

} else {
    // If not a POST request, it's a forbidden action.
    http_response_code(403);
    echo "There was a problem with your submission, please try again.";
}
?>