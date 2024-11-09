<?php
include "../dbconnect.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_form.php");
    exit();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Order</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex w-full h-screen"> <!-- ตั้งค่าความสูงเต็มหน้าจอ -->

        <?php include 'sidebar.php'; ?>

        <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg">

            <!-- Search & Slide Tabs -->
            <div class="p-4 border-b border-gray-200 flex items-center">
                <form method="GET" action="" class="flex-1">
                    <input type="text" name="search" placeholder="ค้นหาโดยวันที่สั่งซื้อ"
                        class="p-2 border border-gray-300 rounded-lg w-full">
                </form>
                <div class="flex ml-4 space-x-4">
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('all')">ทั้งหมด</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('pending')">รอชำระเงิน</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('checking')">รอตรวจสอบ</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('shipping')">กำลังจัดส่ง</button>
                </div>
            </div>

            <!-- Orders Table -->
            <div id="orders-table" class="p-6">
                <table class="w-full table-auto text-left">
                    <thead>
                        <tr class="text-gray-600 border-b border-gray-200">
                            <th class="py-2 px-4">#</th>
                            <th class="py-2 px-4">ชื่อผู้สั่งซื้อ</th>
                            <th class="py-2 px-4">วันที่</th>
                            <th class="py-2 px-4">สถานะ</th>
                            <th class="py-2 px-4">ยอดรวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        // ดึงค่า search จากฟอร์ม (ถ้ามี)
                        $search = isset($_GET['search']) ? $_GET['search'] : '';

                        // Query คำสั่งซื้อ
                        $order_query = "
                        SELECT orders.order_id, orders.total_price, orders.order_status, orders.order_date, userdata.first_name, userdata.last_name
                        FROM orders
                        JOIN userdata ON orders.user_id = userdata.user_id
                        WHERE orders.order_date LIKE '%$search%'
                        ORDER BY orders.order_date DESC
                    ";
                        $result = mysqli_query($conn, $order_query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr class='border-b'>";
                                echo "<td class='py-3 px-4'>" . $row['order_id'] . "</td>";
                                echo "<td class='py-3 px-4'>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                echo "<td class='py-3 px-4'>" . $row['order_date'] . "</td>";
                                echo "<td class='py-3 px-4'>" . $row['order_status'] . "</td>";
                                echo "<td class='py-3 px-4'>฿ " . number_format($row['total_price'], 2) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='py-3 px-4 text-center text-gray-500'>ไม่มีคำสั่งซื้อที่ค้นหา</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function filterOrders(status) {
            console.log(`Filtering orders by status: ${status}`);
            // Logic การกรองสถานะ order สามารถใส่ได้เพิ่มเติมภายหลัง
        }
    </script>
</body>

</html>