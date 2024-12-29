<!-- confirm_logout.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="logout-container">
        <h1>Logout</h1>
        <p>Are you sure you want to log out?</p>
        <form action="logout.php" method="POST">
            <button type="submit">Yes, Logout</button>
            <a href="profile.php">Cancel</a>
        </form>
    </div>
</body>
</html>
