<?php
session_start();
include("dbconnect.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "กรุณาล็อกอินก่อนเพื่อดูคำสั่งซื้อ";
    exit();
}

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session

// ดึงข้อมูลคำสั่งซื้อของผู้ใช้
$order_query = "
    SELECT orders.order_id, orders.total_price, orders.order_status, orders.order_date
    FROM orders
    WHERE orders.user_id = '$user_id'
    ORDER BY orders.order_date DESC
";

$result = mysqli_query($conn, $order_query);

if (!$result) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูลคำสั่งซื้อ: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <?php include 'nav_bar.php'; // เมนูนำทาง ?>

    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-5 text-center">รายการคำสั่งซื้อของฉัน</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                                ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                                Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">View
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['order_id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['order_date']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['total_price'], 2); ?> บาท
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo ucfirst($row['order_status']); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="order_detail.php?order_id=<?php echo $row['order_id']; ?>"
                                        class="text-indigo-600 hover:text-indigo-900">ดูรายละเอียด</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">ไม่มีคำสั่งซื้อในขณะนี้</p>
        <?php endif; ?>
    </div>

</body>

</html>