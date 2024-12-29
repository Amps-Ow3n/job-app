<!-- job_listings.php -->
<?php
// Start session to access user data
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /Hire_Hub/app/views/user_login.php");
    exit();
}

// Include necessary files (DB connection, etc.)
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php';// Adjust path as needed

// Sample code to fetch job listings (this is just an example)
$query = "SELECT * FROM jobs"; // Adjust this query based on your database structure
$stmt = $db->prepare($query);
$stmt->execute();
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Listings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .job-listings-container {
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

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            border-bottom: 1px solid #dddddd;
            padding: 15px 0;
        }

        li:last-child {
            border-bottom: none;
        }

        h3 {
            margin: 0;
            font-size: 18px;
            color: #007bff;
        }

        p {
            margin: 10px 0;
            font-size: 16px;
            color: #333333;
        }

        .details-button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
        }

        .details-button:hover {
            background-color: #0056b3;
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
    <div class="job-listings-container">
        <h1>Job Listings</h1>
        <a href="search_jobs.php" class="btn">Search Jobs</a>
        <ul>
            <?php foreach ($jobs as $job): ?>
                <li>
                    <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                    <p><?php echo htmlspecialchars($job['description']); ?></p>
                    <p>Location: <?php echo htmlspecialchars($job['location']); ?></p>
                    <a href="job_details.php?id=<?php echo $job['id']; ?>" class="details-button">Job Details</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <p><a href="add_report.php">Add Report</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>

