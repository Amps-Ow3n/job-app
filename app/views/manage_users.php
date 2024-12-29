<?php
// Ensure the correct path to the db.php file
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php'; 

// Fetch users from the database
$query = "SELECT id, name, email, user_type FROM users";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all users as an associative array
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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

        p {
            font-size: 16px;
            color: #333333;
            text-align: center;
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
        <h1>Manage Users</h1>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users from the database
                foreach ($users as $user) {
                    echo "<tr>
                            <td>{$user['id']}</td>
                            <td>{$user['name']}</td>
                            <td>{$user['email']}</td>
                            <td>{$user['user_type']}</td>
                            <td>
                                <a href='edit_user.php?id={$user['id']}'>Edit</a> | 
                                <a href='delete_user.php?id={$user['id']}'>Delete</a>
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
