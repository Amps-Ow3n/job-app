<?php
// Ensure the correct path to your db.php file
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php'; // Adjust path as necessary

// Fetch jobs from the database
$query = "SELECT id, title, category, location, description, company, employer FROM jobs";
$stmt = $db->prepare($query);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all jobs as an associative array
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .manage-container {
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
            text-align: center;
        }

        .btn {
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #dddddd;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="manage-container">
        <h1>Manage Jobs</h1>
        <p><a href="add_job.php" class="btn">Add Job</a></p>
        <table>
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Company</th>
                    <th>Employer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch jobs from the database
                foreach ($jobs as $job) {
                    echo "<tr>
                            <td>{$job['id']}</td>
                            <td>{$job['title']}</td>
                            <td>{$job['category']}</td>
                            <td>{$job['location']}</td>
                            <td>{$job['description']}</td>
                            <td>{$job['company']}</td>
                            <td>{$job['employer']}</td>
                            <td>
                                <a href='edit_job.php?id={$job['id']}'>Edit</a> | 
                                <a href='delete_job.php?id={$job['id']}'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <p><a href="admin_dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
