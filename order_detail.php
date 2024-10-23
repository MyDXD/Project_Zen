<?php
session_start();
include("dbconnect.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo "กรุณาล็อกอินก่อนเพื่อดูคำสั่งซื้อ";
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'];

// ตรวจสอบว่าคำสั่งซื้อนั้นเป็นของผู้ใช้ปัจจุบันหรือไม่
$order_query = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'";
$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    echo "ไม่พบคำสั่งซื้อ";
    exit();
}

// ดึงรายการสินค้าในคำสั่งซื้อ
$order_items_query = "
    SELECT oi.product_id, oi.quantity, oi.price, p.product_name, p.product_img
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = '$order_id'
";

$order_items_result = mysqli_query($conn, $order_items_query);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<?php include 'nav_bar.php'; // เมนูนำทาง ?>

<div class="container mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-5 text-center">รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?></h1>

    <?php if (mysqli_num_rows($order_items_result) > 0): ?>
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($item = mysqli_fetch_assoc($order_items_result)): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <img src="<?php echo $item['product_img']; ?>" alt="<?php echo $item['product_name']; ?>" class="h-32 w-32 rounded-md object-cover">
                                <p><?php echo $item['product_name']; ?></p>
                                <p><?php echo number_format($item['price'], 2); ?></p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $item['quantity']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($item['price']*$item['quantity'], 2); ?> บาท</td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-500">ไม่พบรายการสินค้าในคำสั่งซื้อนี้</p>
    <?php endif; ?>
</div>

</body>
</html>
