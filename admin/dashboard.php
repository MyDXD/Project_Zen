<?php
include "../dbconnect.php";
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login_form.php");
    exit();
}

// คำนวณยอดขายรวมจากคำสั่งซื้อที่เสร็จสมบูรณ์ (order_status = 'Completed')
$total_sales_query = "SELECT SUM(total_price) as total_sales FROM orders WHERE order_status = 'Completed'";
$total_sales_result = mysqli_query($conn, $total_sales_query);
$total_sales = mysqli_fetch_assoc($total_sales_result)['total_sales'] ?? 0;

// คำนวณจำนวนสินค้าในสต็อกโดยรวม
$stock_query = "SELECT SUM(stock) as total_stock FROM products";
$stock_result = mysqli_query($conn, $stock_query);
$total_stock = mysqli_fetch_assoc($stock_result)['total_stock'] ?? 0;

// คำนวณคำสั่งซื้อที่รอดำเนินการ (ที่ไม่ใช่ Completed)
$pending_orders_query = "SELECT COUNT(*) as pending_orders FROM orders WHERE order_status != 'Completed'";
$pending_orders_result = mysqli_query($conn, $pending_orders_query);
$pending_orders = mysqli_fetch_assoc($pending_orders_result)['pending_orders'] ?? 0;

// ดึงข้อมูลคำสั่งซื้อล่าสุด เรียงจากใหม่ไปเก่า
$recent_orders_query = "
    SELECT orders.order_id, orders.order_code, orders.total_price, orders.order_status, orders.order_date, 
           userdata.first_name, userdata.last_name
    FROM orders
    JOIN userdata ON orders.user_id = userdata.user_id
    ORDER BY orders.order_date DESC
    LIMIT 10
";
$recent_orders_result = mysqli_query($conn, $recent_orders_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="flex w-full h-screen">
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 p-6 overflow-y-auto h-screen">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Dashboard</h1>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">ยอดขายรวม</h3>
                    <p class="text-3xl font-bold"><?= number_format($total_sales, 2) ?> บาท</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">สินค้าในสต็อก</h3>
                    <p class="text-3xl font-bold"><?= $total_stock ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">คำสั่งซื้อที่รอดำเนินการ</h3>
                    <p class="text-3xl font-bold"><?= $pending_orders ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">ร้าน</h3>
                    <p class="text-3xl font-bold">สานะกิมหยงออนไลน์</p>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold mb-4">คำสั่งซื้อล่าสุด 10 อัน</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ออเดอร์ที่</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ชื่อลูกค้า</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">จำนวนเงินทั้งหมด</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">สถานะ</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">วันที่</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = mysqli_fetch_assoc($recent_orders_result)): ?>
                            <tr>
                                <td class="px-4 py-2"><?= htmlspecialchars($order['order_code']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                                <td class="px-4 py-2"><?= number_format($order['total_price'], 2) ?> บาท</td>
                                <td class="px-4 py-2"><?= htmlspecialchars($order['order_status']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($order['order_date']) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
