<?php
// Include the database connection
require_once '../config/db.php'; // Adjust the path if needed

// Initialize variables
$jobs = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';

    // Validate input
    if (!empty($keyword)) {
        // Prepare SQL query to search for jobs based on the keyword
        $sql = "SELECT * FROM jobs WHERE title LIKE :keyword";
        
        if (!empty($category)) {
            $sql .= " AND category LIKE :category";
        }

        if (!empty($location)) {
            $sql .= " AND location LIKE :location";
        }

        // Prepare and execute the query using $db (which is the connection object)
        $stmt = $db->prepare($sql); // Corrected to use $db
        $stmt->bindValue(':keyword', "%$keyword%");
        if (!empty($category)) {
            $stmt->bindValue(':category', "%$category%");
        }
        if (!empty($location)) {
            $stmt->bindValue(':location', "%$location%");
        }
        $stmt->execute();

        // Fetch the results
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Jobs</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .container {
            max-width: 1000px;
            margin: 80px auto;
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
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .job-results {
            margin-top: 30px;
        }

        .job-results ul {
            list-style-type: none;
            padding: 0;
        }

        .job-results li {
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }

        .job-results h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .job-results p {
            margin: 5px 0;
            color: #555;
        }

        .job-results a {
            color: #007bff;
            text-decoration: none;
        }

        .job-results a:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Search Jobs</h1>
        <form action="search_jobs.php" method="POST">
            <label for="keyword">Keyword:</label>
            <input type="text" id="keyword" name="keyword" placeholder="e.g., Developer">

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" placeholder="e.g., IT">

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="e.g., New York">

            <button type="submit">Search</button>
        </form>

        <div class="job-results">
            <?php if (!empty($jobs)): ?>
                <h2>Search Results</h2>
                <ul>
                    <?php foreach ($jobs as $job): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($job['title']); ?></h3>
                            <p><?php echo htmlspecialchars($job['description']); ?></p>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($job['category']); ?></p>
                            <a href="job_details.php?id=<?php echo $job['id']; ?>">View Details</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-results">No jobs found. Try a different search!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
