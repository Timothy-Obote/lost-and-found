<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php'; // Ensure this path is correct

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['phone']) || empty($_POST['password'])) {
        die("<p style='color: red;'>All form fields are required.</p>");
    }

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT); // Secure password hashing

    // Check if username already exists
    $check_sql = "SELECT id FROM users WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("<p style='color: red;'>Error: Username already exists. Please choose another username.</p>");
    }
    $check_stmt->close();

    // Insert into database with phone number
    $sql = "INSERT INTO users (username, email, phone, `password`) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("<p style='color: red;'>Prepare failed: " . $conn->error . "</p>");
    }

    $stmt->bind_param("ssss", $username, $email, $phone, $password);

    if ($stmt->execute()) {
        echo "<div style='text-align: center; margin-top: 20px;'>";
        echo "<p style='color: green; font-size: 18px;'>Registration successful!</p>";
        echo "<a href='index_loggedin.html' style='display: inline-block; padding: 10px 20px; background-color: #0073e6; color: white; text-decoration: none; border-radius: 5px;'>Go to Home</a>";
        echo "</div>";
    } else {
        echo "<p style='color: red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
 