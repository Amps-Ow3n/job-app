<?php
// Start the session (if not already started)
session_start();

// Include necessary files (e.g., database connection, models, etc.)
require_once '../config/db.php';
require_once '../models/Application.php';
require_once '../models/Job.php';

// Ensure the CSRF token is valid (for security)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }

    // Retrieve form data
    $jobId = $_POST['job_id'];  // This is where job_id comes from the form
    $applicantName = $_POST['applicant_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Get the user ID from session (ensure user is logged in)
    if (!isset($_SESSION['user_id'])) {
        die('User not logged in.');
    }
    $userId = $_SESSION['user_id']; // Replace with the actual user ID from your session

    // Handle file upload (resume)
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == 0) {
        $allowedFileTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $fileTmpPath = $_FILES['resume']['tmp_name'];
        $fileName = $_FILES['resume']['name'];
        $fileSize = $_FILES['resume']['size'];
        $fileType = $_FILES['resume']['type'];

        // Validate file type
        if (in_array($fileType, $allowedFileTypes)) {
            // Set file upload path (you can change this)
            $uploadPath = '../uploads/resumes/' . basename($fileName);
            
            // Move the uploaded file to the desired directory
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                // If file uploaded successfully, save the application data
                try {
                    // Debug: Print the values before attempting to insert
                    echo "Job ID: $jobId<br>";
                    echo "User ID: $userId<br>";
                    echo "Applicant Name: $applicantName<br>";
                    echo "Email: $email<br>";
                    echo "Phone: $phone<br>";
                    echo "Resume Path: $uploadPath<br>";

                    // Create Application model instance
                    $application = new Application($db);
                    $success = $application->applyForJob($applicantName, $email, $phone, $uploadPath, $jobId, $userId);

                    if ($success) {
                        // Show confirmation message
                        echo "Application submitted successfully!";
                    } else {
                        // Log error and display message
                        echo "Error submitting the application. Please try again later.";
                    }
                } catch (Exception $e) {
                    // Log exception for debugging
                    error_log("Exception: " . $e->getMessage());
                    echo "Error submitting the application. Please try again later.";
                }
            } else {
                echo 'Error uploading the resume. Please try again.';
            }
        } else {
            echo 'Invalid file type. Only PDF, DOC, and DOCX are allowed.';
        }
    } else {
        echo 'No file uploaded or there was an error uploading the resume.';
    }
} else {
    // If not a POST request, redirect or show an error message
    header('Location: job_application.php');
    exit;
}
?>
