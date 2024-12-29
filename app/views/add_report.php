<?php
session_start();
require_once '../config/db.php'; // Include your database connection

// Check if the user is logged in and has a valid role
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $report_type = $_POST['report_type'];
    $description = $_POST['description'];

    // Insert the report into the database
    $query = "INSERT INTO reports (user_id, report_type, description) VALUES (:user_id, :report_type, :description)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':report_type', $report_type);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Your report has been submitted successfully.';
    } else {
        $_SESSION['message'] = 'There was an error submitting your report.';
    }

    // Redirect back to the form or another page
    header('Location: add_report.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Report</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            color: #333;
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .container h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #007bff;
        }

        .message {
            margin-bottom: 1rem;
            padding: 0.8rem;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        label {
            font-size: 0.9rem;
            font-weight: bold;
            text-align: left;
            color: #555;
        }

        select, textarea {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }

        select:focus, textarea:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
        }

        textarea {
            resize: none;
        }

        button {
            padding: 0.8rem;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #0056b3;
        }

        p a {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9rem;
        }

        p a:hover {
            text-decoration: underline;
        }

        .form-header {
            background-image: url('report-header.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            border-radius: 10px 10px 0 0;
            height: 150px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header"></div>
        <h1>Add Report</h1>

        <!-- Display success or error message -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="message"><?php echo htmlspecialchars($_SESSION['message']); ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <form action="add_report.php" method="POST">
            <label for="report_type">Report Type:</label>
            <select name="report_type" id="report_type" required>
                <option value="harassment">Harassment</option>
                <option value="inappropriate_job_post">Inappropriate Job Post</option>
                <option value="other">Other</option>
            </select>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="5" placeholder="Provide details about the issue" required></textarea>

            <button type="submit">Submit Report</button>
        </form>
        <p><a href="employer_applications.php">Back to Dashboard</a></p>
        <p><a href="job_listings.php">Back to Job Listings</a></p>
    </div>
</body>
</html>
