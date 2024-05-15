<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: https://trinhcuti204.000webhostapp.com/Login");
    exit;
}

$servername = "localhost";
$username = "id22161371_trinhcuti22";
$password = "Trinhcute2004@.1";
$database = "id22161371_ownerid";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy AppID từ cơ sở dữ liệu
$username = $_SESSION['username'];
$sql = "SELECT AppID FROM AuthWeb WHERE Username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $appId = $row["AppID"];
} else {
    $appId = "N/A";
}

// Xử lý tạo key
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_key'])) {
    $keyName = $_POST['key_name'];
    $keyCount = $_POST['key_count'];
    $expiryDate = $_POST['expiry_date'];

    // Hàm tạo key ngẫu nhiên
    function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    // Thêm key vào cơ sở dữ liệu
    $sql = "INSERT INTO AuthKey (KeyMain, HWID, AppID, Time) VALUES ";
    if (strpos($keyName, 'XXXX') !== false) {
        // Nếu có chuỗi 'XXXX' trong keyName
        for ($i = 0; $i < $keyCount; $i++) {
            // Tìm số lượng chữ X trong key
            $numX = substr_count($keyName, 'X');
            $keyPart1 = generateRandomString($numX);
            $key = str_replace('XXXX', $keyPart1, $keyName);
            $sql .= "('$key', 'NOP', '$appId', '$expiryDate'), ";
        }
    } else {
        // Nếu không có chuỗi 'XXXX' trong keyName
        for ($i = 0; $i < $keyCount; $i++) {
            $key = $keyName;
            $sql .= "('$key', 'NOP', '$appId', '$expiryDate'), ";
        }
    }
    $sql = rtrim($sql, ", ");
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("Keys created successfully.");</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Xử lý xóa tất cả key
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_all_keys'])) {
    $sql = "DELETE FROM AuthKey";
    if ($conn->query($sql) === TRUE) {
        echo '<script>alert("All keys deleted successfully.");</script>';
        // Refresh trang sau khi xóa tất cả các key
        header("Refresh:0");
    } else {
        echo "Error deleting keys: " . $conn->error;
    }
}

// Lấy danh sách các key và HWID từ cơ sở dữ liệu
$sql = "SELECT KeyMain, HWID, Time FROM AuthKey";
$keyList = array();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $keyList[] = array(
            'KeyMain' => $row['KeyMain'],
            'HWID' => $row['HWID'],
            'Time' => $row['Time']
        );
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('dep.jpg') fixed;
            background-size: cover;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Đặt một màu nền hoặc màu nền trong suốt */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative; /* Thêm dòng này */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333333;
        }

        .appid-container {
            position: relative;
            margin-bottom: 20px;
        }

        .appid-input {
            width: calc(100% - 35px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        .copy-button {
            position: absolute;
            top: 0;
            right: 0;
            width: 30px;
            height: 100%;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4caf50;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #cccccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .logout-link {
            text-align: center;
            margin-top: 20px;
        }

        .logout-link a {
            color: #4caf50;
            text-decoration: none;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <div class="appid-container">
            <label for="appid">Your APP ID:</label>
            <input type="text" class="appid-input" id="appid" value="<?php echo $appId; ?>" readonly>
            <button class="copy-button" onclick="copyAppID()"><i class="fas fa-copy"></i></button>
        </div>
        <form method="post">
            <label for="key_name">Key Name:</label>
            <input type="text" id="key_name" name="key_name" required><br>
            
            <label for="key_count">Number of Keys:</label>
            <input type="number" id="key_count" name="key_count" required><br>
            
            <label for="expiry_date">Expiry Date (YYYY-MM-DD):</label>
            <input type="date" id="expiry_date" name="expiry_date" required><br><br>
            
            <input type="submit" name="create_key" value="Create Key">
        </form>
        <table>
            <tr>
                <th>Key</th>
                <th>HWID</th>
                <th>Expiry Date</th>
            </tr>
            <?php foreach ($keyList as $keyItem): ?>
            <tr>
                <td><?php echo $keyItem['KeyMain']; ?></td>
                <td><?php echo $keyItem['HWID']; ?></td>
                <td><?php echo $keyItem['Time']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <form method="post">
            <input type="submit" name="delete_all_keys" value="Delete All Keys">
        </form>
        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <script>
        function copyAppID() {
            var copyText = document.getElementById("appid");
            copyText.select();
            document.execCommand("copy");
            alert("AppID copied to clipboard: " + copyText.value);
        }
    </script>
</body>
</html>


