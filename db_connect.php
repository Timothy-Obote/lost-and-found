<?php
$servername = "localhost"; // Change if using an external database
$username = "root"; // Default MySQL username
$password = ""; // Default password (empty if using XAMPP)
$dbname = "lostnfound_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
