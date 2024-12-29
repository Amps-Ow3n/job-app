<!-- manage_applications.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="manage-container">
        <h1>Manage Job Applications</h1>
        <table>
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Job Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch applications from the database
                foreach ($applications as $application) {
                    echo "<tr>
                            <td>{$application['id']}</td>
                            <td>{$application['applicant_name']}</td>
                            <td>{$application['email']}</td>
                            <td>{$application['phone']}</td>
                            <td>{$application['title']}</td>
                            <td>
                                <a href='view_application.php?id={$application['id']}'>View</a> | 
                                <a href='delete_application.php?id={$application['id']}'>Delete</a>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
        <p><a href="employer_applications.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
