<?php
session_start();

// Include necessary files and initialize database connection
require_once '../controllers/JobApplicationController.php';
require_once '../config/db.php';

$jobApplicationController = new JobApplicationController($db);
$applications = $jobApplicationController->getApplications();

// Pass applications and messages to the view
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted application ID and new status
    $applicationId = $_POST['application_id'] ?? null;
    $newStatus = $_POST['status'] ?? null;

    // Validate inputs
    if ($applicationId && $newStatus) {
        // Call the controller method to update the status
        $isUpdated = $jobApplicationController->updateApplicationStatus($applicationId, $newStatus);

        // Set a success or error message based on the update result
        if ($isUpdated) {
            $_SESSION['message'] = "Application status updated successfully.";
        } else {
            $_SESSION['message'] = "Failed to update application status. Please try again.";
        }
    } else {
        $_SESSION['message'] = "Invalid application ID or status.";
    }

    // Redirect to avoid form resubmission
    header('Location: employer_applications.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Applications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333333;
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

        .applications-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .applications-table th, .applications-table td {
            padding: 12px 15px;
            border: 1px solid #dddddd;
            text-align: left;
        }

        .applications-table th {
            background-color: #007bff;
            color: white;
        }

        .applications-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .status-form {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-form select {
            padding: 5px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            background-color: #ffffff;
        }

        .status-form button {
            padding: 5px 10px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .status-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Job Applications</h1>

        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table class="applications-table">
            <thead>
                <tr>
                    <th>Job Title</th>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Resume</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                        <td><?php echo htmlspecialchars($application['applicant_name']); ?></td>
                        <td><?php echo htmlspecialchars($application['email']); ?></td>
                        <td><?php echo htmlspecialchars($application['phone']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($application['resume_path']); ?>" target="_blank">View Resume</a></td>
                        <td><?php echo htmlspecialchars($application['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($application['status']); ?></td>
                        <td>
                            <form method="POST" class="status-form">
                                <input type="hidden" name="application_id" value="<?php echo htmlspecialchars($application['application_id']); ?>">
                                <select name="status">
                                    <option value="pending" <?php if ($application['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="approved" <?php if ($application['status'] === 'approved') echo 'selected'; ?>>Approved</option>
                                    <option value="rejected" <?php if ($application['status'] === 'rejected') echo 'selected'; ?>>Rejected</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

