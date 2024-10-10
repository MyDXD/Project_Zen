<?php
// เชื่อมต่อกับฐานข้อมูล
$servername = "localhost";
$username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ""; // เปลี่ยนเป็นรหัสผ่านฐานข้อมูลของคุณ
$dbname = "sanahstore";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลจากตาราง reports
$sql = "SELECT * FROM reports ORDER BY issue_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex">
        <div class="w-64 h-screen bg-gray-800 p-6">
            <h2 class="text-white text-2xl font-semibold mb-8">Admin Dashboard</h2>
            <ul>
                <li class="mb-4">
                    <a href="dashboard.php" class="text-gray-300 hover:text-white">แดชบอร์ด</a>
                </li>
                <li class="mb-4">
                    <a href="#" class="text-gray-300 hover:text-white">คำสั่งซื้อ</a>
                </li>
                <li class="mb-4">
                    <a href="products.php" class="text-gray-300 hover:text-white">สินค้า</a>
                </li>
                <li class="mb-4">
                    <a href="customer_data.php" class="text-gray-300 hover:text-white">ลูกค้า</a>
                </li>
                <li class="mb-4">
                    <a href="report_admin.php" class="text-gray-300 hover:text-white">รายงาน</a>
                </li>
            </ul>
        </div>
        <div class="flex-1 p-6">
            <!-- Navbar -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">รายงานปัญหา</h1>
                <div class="flex items-center">
                    <input type="text" placeholder="ค้นหา..." class="p-2 rounded-md border border-gray-300 mr-4">
                    <a href="../logout.php"><button class="bg-gray-800 text-white p-2 rounded-md">ออกจากระบบ</button></a>
                </div>
            </div>

            <!-- Recent Reports Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">รายงาน</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ลำดับ</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">หัวข้อของรายงาน</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">เนื้อหาของรายงาน</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">วันที่รายงาน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // แสดงข้อมูลในแต่ละแถว
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td class='px-4 py-2 text-sm text-gray-700'>" . $row["report_id"] . "</td>";
                                echo "<td class='px-4 py-2 text-sm text-gray-700'>" . htmlspecialchars($row["issue_title"]) . "</td>";
                                echo "<td class='px-4 py-2 text-sm text-gray-700'>" . htmlspecialchars($row["issue_description"]) . "</td>";
                                echo "<td class='px-4 py-2 text-sm text-gray-700'>" . $row["issue_date"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='px-4 py-2 text-sm text-gray-700 text-center'>No reports found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
