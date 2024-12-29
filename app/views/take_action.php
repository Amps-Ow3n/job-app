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
    
    // Fetch the report details
    $query = "SELECT * FROM reports WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $report_id);
    $stmt->execute();
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        $_SESSION['message'] = 'Report not found.';
        header('Location: view_reports.php');
        exit;
    }
} else {
    $_SESSION['message'] = 'Report ID not provided.';
    header('Location: view_reports.php');
    exit;
}

// Handle form submission to update the report status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];

    // Update the report status in the database
    $updateQuery = "UPDATE reports SET status = :status WHERE id = :id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(':status', $status);
    $updateStmt->bindParam(':id', $report_id);

    if ($updateStmt->execute()) {
        $_SESSION['message'] = 'Report status updated successfully.';
    } else {
        $_SESSION['message'] = 'Failed to update report status.';
    }

    // Redirect back to the reports list
    header('Location: view_reports.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Action on Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 1200px;
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

        p {
            font-size: 16px;
            color: #333333;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            color: #333333;
        }

        select {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            width: 200px;
            margin-top: 8px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Take Action on Report</h1>

        <?php if (isset($report)): ?>
            <p><strong>Report Type:</strong> <?php echo htmlspecialchars($report['report_type']); ?></p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($report['description']); ?></p>

            <form action="take_action.php?id=<?php echo $report['id']; ?>" method="POST">
                <label for="status">Update Status:</label>
                <select name="status" id="status" required>
                    <option value="pending" <?php echo $report['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="under investigation" <?php echo $report['status'] === 'under investigation' ? 'selected' : ''; ?>>Under Investigation</option>
                    <option value="resolved" <?php echo $report['status'] === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                </select>
                <br><br>

                <button type="submit">Update Status</button>
            </form>
        <?php else: ?>
            <p>Report not found or invalid ID.</p>
        <?php endif; ?>

        <p><a href="view_reports.php">Back to Reports</a></p>
    </div>
</body>
</html>
