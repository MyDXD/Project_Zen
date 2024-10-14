<?php
$servername = "localhost";
$username = "root";  // เปลี่ยนจาก $dbhost เป็น $username
$password = "123456";
$dbname = "sanahstore";


$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าการเข้ารหัสให้เป็น utf8mb4
$conn->set_charset("utf8mb4");

?>
