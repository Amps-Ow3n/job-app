<?php
require_once 'config/database.php'; // Include your database connection
require_once 'controllers/EmployerController.php';

// Initialize the database connection
$db = new PDO('mysql:host=localhost;dbname=hub', 'root', '');

// Create an instance of the EmployerController
$controller = new EmployerController($db);

// Call the method to view applications
$controller->viewApplications();
