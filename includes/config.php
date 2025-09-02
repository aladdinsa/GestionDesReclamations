<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'reclamation_user');
define('DB_PASSWORD', 'password123');
define('DB_NAME', 'reclamation_db');

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . $conn->connect_error);
}

// Start session
session_start();
?>
