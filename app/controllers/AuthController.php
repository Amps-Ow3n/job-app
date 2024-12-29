<?php
ini_set('display_errors', 1); // Enable error display
error_reporting(E_ALL); // Report all errors

// Ensure the correct path to the db.php file
require_once 'C:/xampp/htdocs/Hire_Hub/app/config/db.php'; // Adjust path based on your folder structure

class AuthController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db; // Store database instance
    }

    // Registration method
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect and sanitize input
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $confirm_password = htmlspecialchars($_POST['confirm_password']);
            $user_type = htmlspecialchars($_POST['user_type']);

            // Check for matching passwords
            if ($password !== $confirm_password) {
                echo "Passwords do not match.";
                return;
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL query
            $query = "INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            
            if ($stmt->execute([$name, $email, $hashed_password, $user_type])) {
                // Redirect to login page
                header("Location: /Hire_Hub/app/views/user_login.php");
                exit;
            } else {
                echo "Registration failed. Please try again.";
            }
        }
    }

    // Handle user login
    public function login() {
        try {
            // Sanitize email input
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password']; // Password needs to be hashed/verified, not sanitized directly
    
            // Check if email and password are provided
            if (!$email || !$password) {
                echo "Please provide both email and password.";
                return false;
            }
    
            // Fetch user data by email
            $query = "SELECT id, name, email, password, user_type FROM users WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
    
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // Debugging step: Check fetched data
            if (!$user) {
                echo "No user found with this email.";
                return false;
            }
    
            // Debugging step: Output fetched user type
            echo "User type fetched: " . $user['user_type'];
    
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Check if user_type is valid
                if (!in_array($user['user_type'], ['admin', 'employer', 'applicant'])) {
                    echo "Invalid user type: " . $user['user_type'];
                    return false;
                }
    
                // Start session and store user details
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
    
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_type'] = $user['user_type'];
    
                // Redirect based on user type
                switch ($user['user_type']) {
                    case 'admin':
                        header('Location: /Hire_Hub/app/views/admin_dashboard.php');
                        break;
                    case 'employer':
                        header('Location: /Hire_Hub/app/views/employer_applications.php');
                        break;
                    case 'applicant':
                        header('Location: /Hire_Hub/app/views/job_listings.php');
                        break;
                    default:
                        echo "Invalid user type: " . $user['user_type'];
                        return false;
                }
                exit();
            } else {
                echo "Invalid email or password.";
                return false;
            }
        } catch (PDOException $e) {
            // Log the error and show a user-friendly message
            error_log("Login Error: " . $e->getMessage());
            echo "An error occurred during login. Please try again.";
            return false;
        }
    }
    
    
    // Handle user logout
    public function logout()
    {
        // Clear session data
        session_unset();
        session_destroy();

        // Redirect to login page
        header("Location: /Hire_Hub/app/views/login.php");
        exit;
    }

    // Utility function to display messages
    private function renderMessage($message)
    {
        echo "<div style='margin: 50px auto; width: 50%; padding: 20px; border: 1px solid #ccc; background: #f9f9f9; text-align: center;'>";
        echo "<p>$message</p>";
        echo "<a href='/Hire_Hub/app/views/login.php' style='text-decoration: none; color: #007bff;'>Go back to login</a>";
        echo "</div>";
    }

    // Fetch user profile
    public function getProfile($userId)
    {
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update user profile
    public function updateProfile()
    {
        session_start();
        $userId = $_SESSION['user_id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            $profilePicture = $_FILES['profile_picture']['name'] ? 'uploads/' . $_FILES['profile_picture']['name'] : null;
            $resume = $_FILES['resume']['name'] ? 'uploads/' . $_FILES['resume']['name'] : null;

            // File uploads
            if ($profilePicture) {
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicture);
            }
            if ($resume) {
                move_uploaded_file($_FILES['resume']['tmp_name'], $resume);
            }

            // Update query
            $query = "UPDATE users SET phone = ?, address = ?, profile_picture = ?, resume = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$phone, $address, $profilePicture, $resume, $userId]);

            echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully.']);
        }
    }
}

// Database connection
try {
    $db = new PDO("mysql:host=localhost;dbname=hub", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create the AuthController instance
$authController = new AuthController($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "POST request received.<br>";
    if (isset($_POST['register'])) {
        echo "Register action triggered.<br>";
        $authController->register();
    } elseif (isset($_POST['login'])) {
        echo "Login action triggered.<br>";
        $authController->login();
    } else {
        echo "No valid action found.<br>";
    }
}

?>
