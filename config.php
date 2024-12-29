<?php
// config.php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'job_app_webapp');
define('DB_USER', 'root');
define('DB_PASS', ''); // Replace with your database password

// Email configuration
define('MAIL_HOST', 'smtp.mailtrap.io'); // Use your mail server
define('MAIL_PORT', 587); // Use your mail server port
define('MAIL_USERNAME', 'your_username'); // Your SMTP username
define('MAIL_PASSWORD', 'your_password'); // Your SMTP password
define('MAIL_FROM', 'no-reply@example.com'); // Email address from which to send emails
define('MAIL_FROM_NAME', 'Job Portal'); // Name to appear in the "From" field

// Session settings
ini_set('session.cookie_lifetime', 86400); // Session lifetime (1 day)
ini_set('session.gc_maxlifetime', 86400); // Maximum session lifetime (1 day)
session_start();

// Timezone settings
date_default_timezone_set('UTC'); // Set your preferred timezone

// Other configurations can go here
?>
