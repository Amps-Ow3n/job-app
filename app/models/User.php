<?php
require_once 'app/config/db.php';

class User {
    private $conn;

    public function __construct() {
        try {
            // Initialize database connection
            $database = new Database();
            $this->conn = $database->connect();
        } catch (PDOException $e) {
            // Log database connection errors
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }

    // User Registration
    public function register($name, $email, $password, $userType) {
        try {
            // Check if email already exists
            $checkQuery = "SELECT id FROM users WHERE email = :email";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(':email', $email);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                // Email already exists
                return ["status" => false, "message" => "Email already registered."];
            }

            // Insert new user into database
            $query = "INSERT INTO users (name, email, password, user_type) VALUES (:name, :email, :password, :user_type)";
            $stmt = $this->conn->prepare($query);

            // Hash the password for security
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Bind parameters
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':user_type', $userType);

            // Execute and return status
            if ($stmt->execute()) {
                return ["status" => true, "message" => "Registration successful."];
            }

            return ["status" => false, "message" => "Failed to register user. Please try again."];
        } catch (PDOException $e) {
            // Log the error and show a user-friendly message
            error_log("Registration Error: " . $e->getMessage());
            return ["status" => false, "message" => "An error occurred during registration. Please try again later."];
        }
    }

    // User Login
    public function login($email, $password) {
        try {
            // Fetch user data by email
            $query = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if ($user && password_verify($password, $user['password'])) {
                // Start session and store user details
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_type'] = $user['user_type'];

                return ["status" => true, "user" => $user]; // Return user details
            }

            // Invalid credentials
            return ["status" => false, "message" => "Invalid email or password."];
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Login Error: " . $e->getMessage());
            return ["status" => false, "message" => "An error occurred during login. Please try again later."];
        }
    }
}
?>
