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

        <div class="flex-1 p-4 h-screen overflow-y-auto">
            <!-- Search & Slide Tabs -->
            <div class="p-3 border-b flex items-center">
                <form method="GET" class="flex-1">
                    <input type="text" name="search" placeholder="ค้นหาด้วยรหัสสั่งซื้อ"
                        class="p-1 border rounded w-96 text-sm" onkeyup="searchOrders()">
                </form>
                <div class="flex space-x-2 ml-2">
                    <button class="text-sm hover:text-blue-500" onclick="filterOrders('all')">ทั้งหมด</button>
                    <button class="text-sm hover:text-blue-500"
                        onclick="filterOrders('Order Placed')">รอชำระเงิน</button>
                    <button class="text-sm hover:text-blue-500"
                        onclick="filterOrders('Payment Received')">รอตรวจสอบ</button>
                    <button class="text-sm hover:text-blue-500"
                        onclick="filterOrders('Order Processing')">ดำเนินการ</button>
                    <button class="text-sm hover:text-blue-500" onclick="filterOrders('Completed')">จัดส่งแล้ว</button>
                    <button class="text-sm hover:text-blue-500" onclick="filterOrders('Cancelled')">ยกเลิก</button>
                </div>
            </div>

            <!-- Orders Table -->
            <div id="orders-table" class="p-4">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="p-2">ID</th>
                            <th class="p-2">รหัสสั่งซื้อ</th>
                            <th class="p-2">ชื่อผู้สั่งซื้อ</th>
                            <th class="p-2">วันที่</th>
                            <th class="p-2">สถานะ</th>
                            <th class="p-2">ยอดรวม</th>
                            <?php if ($status == 'Payment Received') { ?>
                                <th class="p-2">หลักฐาน</th>
                                <th class="p-2">ตรวจสอบ</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr class='border-b'>";
                                echo "<td class='p-2'>" . $row['order_id'] . "</td>";
                                echo "<td class='p-2'>" . $row['order_code'] . "</td>";
                                echo "<td class='p-2'>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                echo "<td class='p-2'>" . $row['order_date'] . "</td>";
                                echo "<td class='p-2'>";
                                $statusMap = [
                                    'Order Placed' => ['รอชำระเงิน', 'bg-yellow-500 text-white'],
                                    'Payment Received' => ['ชำระแล้ว', 'bg-blue-500 text-white'],
                                    'Order Processing' => ['กำลังจัดส่ง', 'bg-orange-500 text-white'],
                                    'Completed' => ['สำเร็จ', 'bg-green-500 text-white'],
                                    'Cancelled' => ['ยกเลิก', 'bg-red-500 text-white']
                                ];
                                $statusInfo = $statusMap[$row['order_status']] ?? ['ไม่ทราบ', 'bg-gray-500 text-white'];
                                echo "<span class='px-2 rounded-full {$statusInfo[1]}'>{$statusInfo[0]}</span>";
                                echo "</td>";
                                echo "<td class='p-2'>฿" . number_format($row['total_price'], 2) . "</td>";

                                if (!empty($row['payment_slip'])) {
                                    echo "<td class='p-2'><img src='../" . htmlspecialchars($row['payment_slip']) . "' alt='Slip' class='w-16 h-16 rounded cursor-pointer' onclick='showFullScreenModal(this.src)'></td>";
                                }
                                if ($row['order_status'] == 'Payment Received') {
                                    echo "<td class='p-2'><button onclick='confirmUpdate(" . $row['order_id'] . ", \"Order Processing\")' class='px-2 py-1 bg-green-500 text-white rounded'>ยืนยัน</button>";
                                    echo "<button onclick='confirmUpdate(" . $row['order_id'] . ", \"Cancelled\")' class='px-2 py-1 bg-red-500 text-white rounded ml-1'>ยกเลิก</button></td>";
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='p-3 text-center text-gray-500'>ไม่มีคำสั่งซื้อ</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div id="fullScreenModal"
                class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
                <span class="absolute top-2 right-4 text-2xl text-white cursor-pointer"
                    onclick="closeFullScreenModal()">&times;</span>
                <img id="modalImage" class="max-w-full max-h-full rounded">
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