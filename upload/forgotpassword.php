<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store token in the database
        $sql = "UPDATE users SET reset_token=?, reset_expires=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expires, $email);
        $stmt->execute();

        // Send email with reset link
        $reset_link = "http://yourwebsite.com/resetpassword.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password:\n\n" . $reset_link;
        $headers = "From: no-reply@yourwebsite.com\r\n";

        if (mail($email, $subject, $message, $headers)) {
            echo "Check your email for the reset link.";
        } else {
            echo "Error sending email.";
        }
    } else {
        echo "Email not found.";
    }
    
    $stmt->close();
    $conn->close();
}
?>
