<?php
$servername = "localhost";
$username = "root";  // เปลี่ยนจาก $dbhost เป็น $username
$password = "";
$dbname = "sanahstore";

$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
