<?php
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php';

// Get the user ID from the query string
$userId = $_GET['id'] ?? null;

// Fetch user details if ID is provided
if ($userId) {
    $query = "SELECT id, name, email, user_type FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found.");
    }
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $userType = $_POST['user_type'] ?? '';

    if ($name && $email && $userType) {
        $updateQuery = "UPDATE users SET name = :name, email = :email, user_type = :user_type WHERE id = :id";
        $stmt = $db->prepare($updateQuery);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':user_type', $userType, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: manage_users.php");
            exit;
        } else {
            $error = "Failed to update user details.";
        }
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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

        .edit-container {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .edit-container h1 {
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #007bff;
            text-align: center;
        }

        .edit-container form {
            display: flex;
            flex-direction: column;
        }

        .edit-container label {
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #555;
        }

        .edit-container input, 
        .edit-container select {
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .edit-container button {
            padding: 0.8rem;
            font-size: 1rem;
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .edit-container button:hover {
            background: #0056b3;
        }

        .edit-container p {
            margin-top: 1rem;
            text-align: center;
        }

        .edit-container p a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }

        .edit-container p a:hover {
            text-decoration: underline;
        }

        .error {
            color: #dc3545;
            margin-bottom: 1rem;
            text-align: center;
        }

        @media (max-width: 768px) {
            .edit-container {
                padding: 1.5rem;
            }

            .edit-container h1 {
                font-size: 1.5rem;
            }

            .edit-container input, 
            .edit-container select {
                font-size: 0.9rem;
            }

            .edit-container button {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit User</h1>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
            
            <label for="user_type">Role:</label>
            <select id="user_type" name="user_type">
                <option value="admin" <?php echo ($user['user_type'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                <option value="employer" <?php echo ($user['user_type'] === 'employer') ? 'selected' : ''; ?>>Employer</option>
                <option value="job_seeker" <?php echo ($user['user_type'] === 'job_seeker') ? 'selected' : ''; ?>>Job Seeker</option>
            </select>
            
            <button type="submit">Update User</button>
        </form>
        <p><a href="manage_users.php">Back to Manage Users</a></p>
    </div>
</body>
</html>
