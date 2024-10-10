<?php
include "../dbconnect.php";

$sql = "SELECT * FROM userdata";
$result = $conn->query($sql);

session_start();

// ตรวจสอบว่าผู้ใช้มี session หรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // ถ้าไม่ใช่ admin หรือไม่พบ session ให้นำผู้ใช้ไปยังหน้า login หรือหน้าอื่นๆ
    header("Location: ../login_form.php"); // หรือเส้นทางที่คุณต้องการ
    exit();
}
?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Admin Dashboard</title>
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

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Navbar -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">Dashboard</h1>
                <div class="flex items-center">
                    <input type="text" placeholder="ค้นหา..." class="p-2 rounded-md border border-gray-300 mr-4">
                </div>
            </div>


            <!-- <div class="flex justify-end space-x-4">
                ปุ่มไปที่หน้า Create Product
                <a href="create_product.php" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    เพิ่มสินค้า 
                </a>

            </div> -->


            <!-- Product Inventory Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">

                <h3 class="text-lg font-semibold mb-4">ข้อมูลลูกค้า</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>

                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">id ลูกค้า</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ชื่อ</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">นามสกุล</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ชื่อเล่น</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ที่อยู่</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">เบอร์โทร</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">เบอร์</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ยศ</th>
                        </tr>

                    </thead>

                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <!-- <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php if (!empty($row['product_img'])): ?>
                                            <img src="./img/<?php echo htmlspecialchars($row['product_img']); ?>"
                                                alt="Product Image" class="w-20 h-20 object-cover">
                                        <?php else: ?>
                                            No image
                                        <?php endif; ?>
                                    </td> -->
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['user_id']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['email']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['first_name']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['last_name']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['title_name']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['address']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['phone_number']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['bank_account_id']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </td>

                                    <td class="px-4 py-2 text-sm text-gray-700">
<!-- <button 
    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
    onclick="window.location.href='edit_product.php?id=<?php echo htmlspecialchars($row['product_id']); ?>'">
    ตรวจสอบข้อมูลลูกค้า
</button> -->

</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-sm text-gray-700 text-center">No products found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>