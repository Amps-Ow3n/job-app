<?php
// Include the necessary files to connect to your database
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php'; // Adjust as needed

session_start(); // Start the session

// Check if the user is logged in (you should have a login check here)
if (!isset($_SESSION['user_id'])) {
    die('You must be logged in to post a job.');
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $title = $_POST['title'] ?? null;
    $category = $_POST['category'] ?? null;
    $location = $_POST['location'] ?? null;
    $description = $_POST['description'] ?? null;
    $company = $_POST['company'] ?? null;
    $employer = $_POST['employer'] ?? null;

    // Validate required fields
    if (!$title || !$category || !$location || !$description || !$company || !$employer) {
        echo "<p>All fields are required. Please try again.</p>";
    } else {
        try {
            // Prepare the SQL query to insert the new job
            $query = "INSERT INTO jobs (title, category, location, company, employer, description, user_id, employer_id) 
                      VALUES (:title, :category, :location, :company, :employer, :description, :user_id, :employer_id)";
            $stmt = $db->prepare($query);

            // Bind the parameters and execute the query
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':company', $company);
            $stmt->bindParam(':employer', $employer);
            $stmt->bindParam(':user_id', $user_id); // Bind the user ID
            $stmt->bindValue(':employer_id', $employer_id);

            if ($stmt->execute()) {
                echo "<p>Job added successfully!</p>";
                // Optionally, redirect to the manage jobs page after adding
                header("Location: manage_jobs.php");
                exit();
            } else {
                echo "<p>Error adding job. Please try again.</p>";
            }
        } catch (PDOException $e) {
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job</title>
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
        }

        .add-job-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .add-job-container h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #007bff;
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

        input[type="text"] {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }

        input[type="text"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
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
            background-image: url('job-header.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
            border-radius: 10px 10px 0 0;
            height: 150px;
        }

        @media (max-width: 768px) {
            .add-job-container {
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
    <div class="add-job-container">
        <div class="form-header"></div>
        <h1>Add New Job</h1>
        <form action="add_job.php" method="POST">
            <label for="title">Job Title:</label>
            <input type="text" id="title" name="title" placeholder="Enter job title" required>

            <label for="category">Category:</label>
            <input type="text" id="category" name="category" placeholder="Enter category" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" placeholder="Enter location" required>

            <label for="description">Description:</label>
            <input type="text" id="description" name="description" placeholder="Enter job description" required>

            <label for="company">Company:</label>
            <input type="text" id="company" name="company" placeholder="Enter company name" required>

            <label for="employer">Employer:</label>
            <input type="text" id="employer" name="employer" placeholder="Enter employer name" required>

            <button type="submit">Add Job</button>
        </form>
        <p><a href="manage_jobs.php">Back to Manage Jobs</a></p>
    </div>
</body>
</html>

