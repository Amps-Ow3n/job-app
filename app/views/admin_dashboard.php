<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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

        .dashboard-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .dashboard-container h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #007bff;
        }

        nav ul {
            list-style: none;
            padding: 0;
        }

        nav ul li {
            margin: 1rem 0;
        }

        nav ul li a {
            text-decoration: none;
            color: #007bff;
            font-size: 1.1rem;
            font-weight: bold;
            padding: 0.8rem 1.2rem;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background: #007bff;
            color: #fff;
        }

        p a {
            color: #dc3545;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
        }

        p a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            nav ul li a {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, Admin</h1>
        <nav>
            <ul>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_jobs.php">Manage Jobs</a></li>
                <li><a href="view_reports.php">View Reports</a></li>
</ul>
        </nav>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
