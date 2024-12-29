<?php
// Include the necessary files to connect to your database
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php'; // Adjust as needed

// Check if the job ID is passed in the URL
if (!isset($_GET['id'])) {
    die('Job ID is required.');
}

$job_id = $_GET['id'];

// Delete the job from the database
$query = "DELETE FROM jobs WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(':id', $job_id);

if ($stmt->execute()) {
    echo "<p>Job deleted successfully!</p>";
    header("Location: manage_jobs.php"); // Redirect to the manage jobs page
    exit();
} else {
    echo "<p>Error deleting job. Please try again.</p>";
}
?>
