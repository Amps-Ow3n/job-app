<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
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

        .edit-job-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .edit-job-container h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #007bff;
        }

        .form-input, .form-textarea {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-textarea {
            resize: none;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .link {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .edit-job-container {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="edit-job-container">
        <h1>Edit Job</h1>
        <form action="edit_job.php?id=<?php echo $job['id']; ?>" method="POST">
            <input type="text" name="job_title" placeholder="Job Title" class="form-input" value="<?php echo $job['title']; ?>" required>
            <textarea name="job_description" placeholder="Job Description" class="form-textarea" rows="5" required><?php echo $job['description']; ?></textarea>
            <input type="text" name="job_salary" placeholder="Salary" class="form-input" value="<?php echo $job['salary']; ?>" required>
            <button type="submit" class="btn">Update Job</button>
        </form>
        <p><a href="manage_jobs.php" class="link">Back to Manage Jobs</a></p>
    </div>
</body>
</html>
