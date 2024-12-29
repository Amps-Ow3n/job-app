<?php
session_start();
require_once '../config/db.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Fetch all reports from the database
$query = "SELECT * FROM reports";
$stmt = $db->prepare($query);
$stmt->execute();
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
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
        <h1>View Reports</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <p><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Report ID</th>
                    <th>Report Type</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $report): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($report['id']); ?></td>
                        <td><?php echo htmlspecialchars($report['report_type']); ?></td>
                        <td><?php echo htmlspecialchars($report['description']); ?></td>
                        <td><?php echo htmlspecialchars($report['status']); ?></td>
                        <td><a href="take_action.php?id=<?php echo $report['id']; ?>">Take Action</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
