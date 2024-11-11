<script src="https://cdn.tailwindcss.com"></script>

<?php
include "../dbconnect.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_form.php");
    exit();
}

$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : 'all';

$order_query = "
    SELECT orders.order_id, orders.payment_slip, orders.order_code, orders.total_price, orders.order_status, orders.order_date, userdata.first_name, userdata.last_name
    FROM orders
    JOIN userdata ON orders.user_id = userdata.user_id
";

// ค้นหาคำสั่งซื้อ
if (!empty($search)) {
    $search = strtolower($search);
    $order_query .= " WHERE LOWER(orders.order_code) LIKE '%$search%'";
}

// กรองตามสถานะ
if ($status !== 'all') {
    $order_query .= !empty($search) ? " AND" : " WHERE";
    $order_query .= " orders.order_status = '$status'";
}

$order_query .= " ORDER BY orders.order_date DESC";

$result = mysqli_query($conn, $order_query);

// ฟังก์ชันอัปเดตสถานะคำสั่งซื้อเมื่อมีการส่ง AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $order_id = $data['order_id'];
    $new_status = $data['status'];

    $update_query = "UPDATE orders SET order_status = ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_status, $order_id);

    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
    }

    $stmt->close();
    echo json_encode($response);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Order</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex w-full h-screen">

        <?php include 'sidebar.php'; ?>

        <div class="flex-1 p-6 overflow-y-auto h-screen">

            <!-- Search & Slide Tabs -->
            <div class="p-4 border-b border-gray-200 flex items-center">
                <form method="GET" action="" class="flex-1">
                    <input type="text" name="search" id="searchInput" placeholder="ค้นหาโดยรหัสสั่งซื้อ"
                        class="p-2 border border-gray-300 rounded-lg w-full" onkeyup="searchOrders()">
                </form>
                <div class="flex ml-4 space-x-4">
                    <!-- กำหนดให้ปุ่มแต่ละปุ่มใช้ filterOrders โดยส่ง status ที่ต้องการ -->
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('all')">ทั้งหมด</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('Order Placed')">รอชำระเงิน</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('Payment Received')">รอตรวจสอบ</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('Order Processing')">ดำเนินการจัดส่ง</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('Completed')">จัดส่งแล้ว</button>
                    <button class="py-2 px-4 text-gray-600 hover:text-blue-500 focus:outline-none"
                        onclick="filterOrders('Cancelled')">ยกเลิก</button>
                </div>
            </div>

            <!-- Orders Table -->
            <div id="orders-table" class="p-6">
                <table class="w-full table-auto text-left">
                    <thead>
                        <tr class="text-gray-600 border-b border-gray-200">
                            <th class="py-2 px-4">ID</th>
                            <th class="py-2 px-4">รหัสคำสั่งซื้อ</th>
                            <th class="py-2 px-4">ชื่อผู้สั่งซื้อ</th>
                            <th class="py-2 px-4">วันที่</th>
                            <th class="py-2 px-4">สถานะ</th>
                            <th class="py-2 px-4">ยอดรวม</th>
                            <?php if ($status == 'Payment Received') { ?>
                                <th class="py-2 px-4">หลักฐานการชำระเงิน</th>
                                <th class="py-2 px-4">ตรวจสอบ</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr class='border-b'>";
                                echo "<td class='py-3 px-4'>" . $row['order_id'] . "</td>";
                                echo "<td class='py-3 px-4'>" . $row['order_code'] . "</td>";
                                echo "<td class='py-3 px-4'>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                echo "<td class='py-3 px-4'>" . $row['order_date'] . "</td>";
                                // สถานะ
                                echo "<td class='px-6 py-4 whitespace-nowrap'>";
                                $statusText = '';
                                $statusColor = '';

                                switch ($row['order_status']) {
                                    case 'Order Placed':
                                        $statusText = 'รอชำระเงิน';
                                        $statusColor = 'bg-yellow-500 text-white';
                                        break;
                                    case 'Payment Received':
                                        $statusText = 'ชำระเงินแล้ว รอตรวจสอบ';
                                        $statusColor = 'bg-blue-500 text-white';
                                        break;
                                    case 'Order Processing':
                                        $statusText = 'กำลังจัดส่ง';
                                        $statusColor = 'bg-orange-500 text-white';
                                        break;
                                    case 'Completed':
                                        $statusText = 'จัดส่งสำเร็จ';
                                        $statusColor = 'bg-green-500 text-white';
                                        break;
                                    case 'Cancelled':
                                        $statusText = 'ยกเลิก';
                                        $statusColor = 'bg-red-500 text-white';
                                        break;
                                    default:
                                        $statusText = 'ไม่ทราบสถานะ';
                                        $statusColor = 'bg-gray-500 text-white';
                                        break;
                                }
                                echo "<span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full $statusColor'>$statusText</span>";
                                echo "</td>";
                                echo "<td class='py-3 px-4'>฿ " . number_format($row['total_price'], 2) . "</td>";
                                // แสดงรูปภาพหลักฐานการชำระเงิน (ถ้ามี)
                                echo "<td class='py-3 px-4 flex items-center'>";
                                if (!empty($row['payment_slip'])) {
                                    // หากมีสลิปแสดงรูปภาพหลักฐานการชำระเงิน
                                    echo "<img src='../" . htmlspecialchars($row['payment_slip']) . "' alt='Slip Image' class='w-24 h-24 rounded-md cursor-pointer' onclick='showFullScreenModal(this.src)' />";
                                }
                                echo "</td>";

                                // แสดงปุ่มยืนยันและยกเลิกเมื่อสถานะคือ 'Payment Received'
                                if ($row['order_status'] == 'Payment Received') {
                                    echo "<td class='py-3 px-4'>";
                                    echo "<button onclick='confirmUpdate(" . $row['order_id'] . ", \"Order Processing\")' class='px-4 py-2 bg-green-500 text-white rounded-md'>ยืนยัน</button>";
                                    echo "<button onclick='confirmUpdate(" . $row['order_id'] . ", \"Cancelled\")' class='px-4 py-2 bg-red-500 text-white rounded-md ml-2'>ยกเลิก</button>";
                                    echo "</td>";
                                }
                                echo "</tr>";
                            }

                        } else {
                            echo "<tr><td colspan='5' class='py-3 px-4 text-center text-gray-500'>ไม่มีคำสั่งซื้อที่ค้นหา</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="fullScreenModal"
                class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 flex items-center justify-center z-50">
                <span class="absolute top-4 right-6 text-white text-3xl cursor-pointer"
                    onclick="closeFullScreenModal()">&times;</span>
                <img id="modalImage" class="max-w-full max-h-full rounded-lg" alt="Full Screen Slip">
            </div>

        </div>
    </div>
    <script>
        function showFullScreenModal(src) {
            const modal = document.getElementById("fullScreenModal");
            const modalImage = document.getElementById("modalImage");
            modalImage.src = src;
            modal.classList.remove("hidden");
        }

        // ฟังก์ชันปิด Modal
        function closeFullScreenModal() {
            const modal = document.getElementById("fullScreenModal");
            modal.classList.add("hidden");
        }

        function filterOrders(status) {
            const url = new URL(window.location.href);

            // ลบค่า search ออกจาก URL ถ้าเลือกสถานะใดๆ
            url.searchParams.delete('search');

            // ตั้งค่าพารามิเตอร์ status
            url.searchParams.set('status', status);

            // เปลี่ยน URL โดยไม่โหลดหน้าใหม่
            window.location.href = url;
        }

        function searchOrders() {
            const searchInput = document.getElementById('searchInput').value;

            // ใช้ AJAX เพื่อส่งคำค้นหาไปที่ PHP
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'search_orders.php?search=' + encodeURIComponent(searchInput), true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // รับข้อมูลจาก server และแสดงในแท็กที่ต้องการ
                    document.getElementById('orders-table').innerHTML = xhr.responseText;
                }
            };

            xhr.send();
        }

        // ฟังก์ชันแสดงการยืนยันการอัปเดตสถานะด้วย SweetAlert2
        function confirmUpdate(orderId, newStatus) {
            const statusText = newStatus === "Order Processing" ? "ยืนยันคำสั่งซื้อ" : "ยกเลิกคำสั่งซื้อ";
            const statusColor = newStatus === "Order Processing" ? "#3085d6" : "#d33";

            Swal.fire({
                title: `คุณต้องการ ${statusText} หรือไม่?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: statusColor,
                cancelButtonColor: '#aaa',
                confirmButtonText: 'ใช่, ฉันต้องการ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateOrderStatus(orderId, newStatus);
                }
            });
        }

        // ฟังก์ชันในการอัปเดตสถานะคำสั่งซื้อโดยใช้ AJAX
        function updateOrderStatus(orderId, newStatus) {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ order_id: orderId, status: newStatus })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'สำเร็จ!',
                            text: `สถานะได้ถูกอัปเดตเป็น "${newStatus}"`,
                            icon: 'success',
                            confirmButtonText: 'ตกลง'
                        }).then(() => {
                            location.reload(); // รีเฟรชหน้าหลังการอัปเดต
                        });
                    } else {
                        Swal.fire({
                            title: 'เกิดข้อผิดพลาด',
                            text: 'การอัปเดตสถานะล้มเหลว',
                            icon: 'error',
                            confirmButtonText: 'ตกลง'
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>