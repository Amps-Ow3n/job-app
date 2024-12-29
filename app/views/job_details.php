<?php
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php';

if (!isset($_GET['id'])) {
    die('Job ID is missing.');
}

$jobId = intval($_GET['id']);

try {
    $query = "SELECT * FROM jobs WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $jobId, PDO::PARAM_INT);
    $stmt->execute();
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job) {
        die('Job not found.');
    }
} catch (PDOException $e) {
    die('Error fetching job details: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($job['title']); ?> - Job Details</title>
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

        p {
            font-size: 16px;
            color: #333333;
            margin-bottom: 15px;
        }

        strong {
            font-weight: bold;
        }

        a.apply-button {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            text-align: center;
        }

        a.apply-button:hover {
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
        <h1><?php echo htmlspecialchars($job['title']); ?></h1>
        <p><strong>Company:</strong> <?php echo htmlspecialchars($job['company']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
        <a href="job_application.php?id=<?php echo $job['id']; ?>" class="apply-button">Apply for Job</a>
    </div>
</body>
</html>

