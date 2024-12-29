<?php
// Include the database connection
require_once 'app/config/db.php'; // Adjust this to your actual database connection file path

// Create a database connection
$dsn = 'mysql:host=localhost;dbname=hub';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Include the AuthController
require_once 'app/controllers/AuthController.php'; // Adjust this to your actual AuthController file path

// Create an instance of AuthController
$authController = new AuthController($db);

// Handle POST requests for login or registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        // Trigger the register action
        $authController->register();
    } elseif (isset($_POST['login'])) {
        // Trigger the login action
        $authController->login();
    }
} else {
    // Redirect to the login page for other (GET) requests
    header('Location: app/views/user_login.php');
    exit();
}
?>
