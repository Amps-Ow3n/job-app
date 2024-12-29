<?php
// Start the session if it's not already started
session_start();

// Include necessary files (e.g., database connection, models, etc.)
require_once '../config/db.php';
require_once '../models/Job.php';

// Check if the job ID is set (from URL or session)
if (isset($_GET['id'])) {
    $jobId = $_GET['id'];  // Get the job ID from URL parameter
} else {
    // Redirect if job ID is not found
    die("Job ID is missing.");
}

// Fetch the job details based on jobId (optional: can be used for dynamic job title or description)
$job = new Job($db);
$jobDetails = $job->getJobById($jobId);

// Check if job exists
if (!$jobDetails) {
    die("Job not found.");
}

// Generate CSRF token if not already set
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Secure token generation
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #333333;
        }

        input[type="text"], input[type="email"], input[type="file"] {
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            background-color: #ffffff;
        }

        button {
            padding: 10px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Apply for the Job: <?php echo htmlspecialchars($jobDetails['title']); ?></h1>

        <!-- Job Application Form -->
        <form method="POST" action="submit_application.php" enctype="multipart/form-data">
            <!-- Hidden field for job_id -->
            <input type="hidden" name="job_id" value="<?php echo $jobId; ?>">

            <!-- Applicant Details -->
            <label for="applicant_name">Full Name:</label>
            <input type="text" name="applicant_name" id="applicant_name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required><br>

            <label for="phone">Phone Number:</label>
            <input type="text" name="phone" id="phone" required><br>

            <label for="resume">Upload Resume:</label>
            <input type="file" name="resume" id="resume" required><br>

            <!-- CSRF Token for security -->
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>

