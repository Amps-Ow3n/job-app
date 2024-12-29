<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        .auth-container {
            max-width: 400px;
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

        label {
            display: block;
            font-weight: bold;
            color: #333333;
            margin-top: 10px;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message, .success-message {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
        }

        p {
            text-align: center;
            font-size: 14px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <h1>Login</h1>

        <!-- Feedback messages -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php elseif (isset($_GET['success'])): ?>
            <div class="success-message">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </div>
        <?php endif; ?>

        <form action="/Hire_Hub/index.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <p>Don't have an account? <a href="/Hire_Hub/app/views/user_registration.php">Register here</a>.</p>
    </div>
</body>
</html>
