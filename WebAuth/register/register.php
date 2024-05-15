<?php
// Database connection
$servername = "localhost";
$username = "id22161371_trinhcuti22";
$password = "Trinhcute2004@.1";
$database = "id22161371_ownerid";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = $_POST['username'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    echo "Passwords do not match.";
    exit;
}

// Check password length
if (strlen($password) < 6) {
    echo "Password must be at least 6 characters long.";
    exit;
}

// Generate random AppID
$appId = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);

// Insert user data into database
$sql = "INSERT INTO AuthWeb (Username, Password, AppID) VALUES ('$username', '$password', '$appId')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful.";
    sleep(5);
ob_start(); // Bắt đầu bộ đệm đầu ra

// Code của bạn ở đây

echo "<meta http-equiv='refresh' content='0;url=https://trinhcuti204.000webhostapp.com/Login'>";

exit();

ob_end_flush(); // Gửi đầu ra đã được lưu trữ từ bộ đệm
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
