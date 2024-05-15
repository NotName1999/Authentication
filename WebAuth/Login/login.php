<?php
session_start();

$servername = "localhost";
$username = "id22161371_trinhcuti22";
$password = "Trinhcute2004@.1";
$database = "id22161371_ownerid";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM AuthWeb WHERE Username='$username' AND Password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Đăng nhập thành công, chuyển hướng đến trang web khác
    $_SESSION['username'] = $username;
    header("Location: https://trinhcuti204.000webhostapp.com/Welcome.php");
} else {
    echo "Đăng nhập không thành công. Vui lòng kiểm tra lại tên đăng nhập và mật khẩu.";
}

$conn->close();
?>
