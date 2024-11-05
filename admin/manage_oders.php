<?php
include "../dbconnect.php";
session_start();

// ตรวจสอบว่าเป็น admin หรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_form.php");
    exit();
}


// การค้นหาคำสั่งซื้อทั้งหมด
$order_query = "
    SELECT orders.order_id, orders.total_price, orders.order_status, orders.order_date, orders.payment_slip, userdata.first_name , userdata.last_name
    FROM orders
    JOIN userdata ON orders.user_id = userdata.user_id
    WHERE orders.order_date LIKE '%$search%'
    ORDER BY orders.order_date DESC
";
$result = mysqli_query($conn, $order_query);

$result = mysqli_query($conn, $order_query);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-5 text-center">รายการคำสั่งซื้อทั้งหมด</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อผู้ใช้</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ดูรายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
        <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['order_date'] ?? 'ไม่ระบุ'); ?></td>
            <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['total_price'], 2); ?> บาท</td>
            <td class="px-6 py-4 whitespace-nowrap"><?php echo ucfirst(htmlspecialchars($row['order_status'] ?? 'ไม่ระบุ')); ?></td>
            <td class="px-6 py-4 whitespace-nowrap">
                <a href="order_detail.php?order_id=<?php echo htmlspecialchars($row['order_id']); ?>" class="text-indigo-600 hover:text-indigo-900">
                    ดูรายละเอียด
                </a>
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
