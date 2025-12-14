<?php
session_start();
include 'db_connect.php'; // Ensure database connection is correct

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error_message'] = "You must be logged in to submit an item.";
        header("Location: submit_item.php");
        exit;
    }

    // Fetch form data securely
    $user_id = $_SESSION['user_id'];
    $item_type = htmlspecialchars($_POST['itemType']);
    $itemName = htmlspecialchars($_POST['itemName']);
    $location = htmlspecialchars($_POST['location']);
    $date_time = htmlspecialchars($_POST['dateTime']); // Fixed field name
    $description = htmlspecialchars($_POST['description']);
    $phone_number = htmlspecialchars($_POST['phoneNumber']); // Fixed field name

    // File upload handling
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $imagePath = null;
    if (isset($_FILES["itemImage"]) && $_FILES["itemImage"]["error"] === UPLOAD_ERR_OK) {
        $imageName = time() . "_" . basename($_FILES["itemImage"]["name"]);
        $targetFilePath = $targetDir . $imageName;

        if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $targetFilePath)) {
            $imagePath = $targetFilePath;
        } else {
            $_SESSION['error_message'] = "File upload failed.";
            header("Location: submit_item.php");
            exit;
        }
    }

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO lost_items (user_id, item_type, itemName, location, description, image, date_time, phone_number) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        $_SESSION['error_message'] = "Database prepare error: " . $conn->error;
        header("Location: submit_item.php");
        exit;
    }

    $stmt->bind_param("isssssss", $user_id, $item_type, $itemName, $location, $description, $imagePath, $date_time, $phone_number);

    if ($stmt->execute()) {
        echo "<div style='text-align: center; margin-top: 20px;'>
                <p style='color: green; font-size: 20px; font-weight: bold;'>Item uploaded successfully!</p>
                <button onclick='goHome()' style='padding: 10px 20px; font-size: 16px; background-color: #3c99dc; color: white; border: none; border-radius: 5px; cursor: pointer;'>Go to Home</button>
              </div>
              <script>
                  function goHome() {
                      window.location.href = 'index_loggedin.html';
                  }
              </script>";
    } else {
        echo "<p style='color: red; text-align: center; font-size: 18px;'>Database error: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
