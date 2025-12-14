<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'db_connect.php';

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$sql = "SELECT id, username, email, phone, last_login FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User data not found.");
}

// First letter of username for avatar
$avatar = strtoupper($user['username'][0]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 40px;
            transition: background-color 0.3s, color 0.3s;
        }
        .dark-mode {
            background-color: #121212;
            color: white;
        }
        .container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, color 0.3s;
        }
        .dark-mode .container {
            background: #333;
            color: white;
        }
        .avatar {
            width: 80px;
            height: 80px;
            background: #007bff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            border-radius: 50%;
            margin: 10px auto;
        }
        .welcome {
            font-size: 24px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            display: inline-block;
            animation: slide 3s linear infinite;
        }
        @keyframes slide {
            0% { transform: translateX(0); }
            50% { transform: translateX(10px); }
            100% { transform: translateX(0); }
        }
        .profile-section p {
            background: #e9ecef;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }
        .dark-mode .profile-section p {
            background: #555;
            color: white;
        }
        .theme-toggle, .home-button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            background: #007bff;
            color: white;
            border-radius: 5px;
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="avatar"> <?php echo $avatar; ?> </div>
        <h1 class="welcome">Welcome, <?php echo htmlspecialchars($user['username']); ?></h1>
        <div class="profile-section">
            <p><strong>Email:</strong> <br> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <br> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Last Login:</strong> <br> <?php echo htmlspecialchars($user['last_login']); ?></p>
        </div>
        <button class="theme-toggle" onclick="toggleTheme()">Toggle Dark Mode</button>
        <button class="home-button" onclick="location.href='index_loggedin.html'"> Home</button>
    </div>
    <script>
        function toggleTheme() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
        }
        if (localStorage.getItem('theme') === 'dark') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>
