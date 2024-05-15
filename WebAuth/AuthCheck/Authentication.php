<?php

$servername = "localhost";
$username = "id22161371_trinhcuti22";
$password = "Trinhcute2004@.1";
$database = "id22161371_ownerid";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $Keyget = $_POST['Key'];
    $Hwidget = $_POST['hwid'];
    $AppID = $_POST['appid'];

    // Kiểm tra AppID và KeyMain có trong cùng hàng không
    $sqlselect = "SELECT * FROM AuthKey WHERE `KeyMain` = '$Keyget' AND `AppID` = '$AppID'";
    $result = $conn->query($sqlselect);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kiểm tra xem key đã hết hạn chưa
        $currentDate = date("Y-m-d");
        if ($row['Time'] < $currentDate) {
            die("Error: Key has expired");
        }

        if ($row['HWID'] == "NOP") {
            $UpdateSQL = "UPDATE AuthKey SET `HWID` = '$Hwidget' WHERE `KeyMain` = '$Keyget'";
            if ($conn->query($UpdateSQL) === TRUE) {
                echo "Logged";
            } else {
                die("Error");
            }
        } else if ($row['HWID'] == $Hwidget) {
            echo 'Logged';
        } else {
            die('ERROR');
        }
    } else {
        die("Error");
    }
}

?>
