<?php
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php';

// Get the user ID from the query string
$userId = $_GET['id'] ?? null;

if ($userId) {
    $deleteQuery = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($deleteQuery);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Redirect back to manage_users.php with a success message
        header("Location: manage_users.php");
        exit;
    } else {
        die("Failed to delete the user.");
    }
} else {
    die("User ID is required.");
}
