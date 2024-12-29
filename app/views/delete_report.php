<?php
session_start();
require_once '../config/db.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Get the report ID from the query string
if (isset($_GET['id'])) {
    $report_id = $_GET['id'];
    
    // Delete the report from the database
    $query = "DELETE FROM reports WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $report_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Report deleted successfully.';
    } else {
        $_SESSION['message'] = 'Failed to delete the report.';
    }
}

header('Location: admin_reports.php');
exit;
