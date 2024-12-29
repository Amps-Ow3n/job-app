<?php
// db.php
class Database {
    private $host = 'localhost';
    private $db_name = 'hub';
    private $username = 'root'; // Default for XAMPP
    private $password = ''; // Default for XAMPP
    private $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection error: " . $e->getMessage());
        }
        return $this->conn;
    }
}

// Create a database instance and connect
$database = new Database();
$db = $database->connect();

// Ensure the database connection works
if (!$db) {
    die("Database connection failed.");
}
