<?php
// Database credentials
$host     = 'localhost';
$username = 'root';          // Change this if needed
$password = 'root';              // Change this if needed
$database = 'ebook_store';   // Make sure the DB is created first

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Optional: set charset
$conn->set_charset('utf8mb4');
?>
