<?php
include "../dbconnect.php";
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM products WHERE product_name LIKE '%$search%'";
$result = $conn->query(query: $sql);

session_start();

// ตรวจสอบว่าผู้ใช้มี session หรือไม่
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // ถ้าไม่ใช่ admin หรือไม่พบ session ให้นำผู้ใช้ไปยังหน้า login หรือหน้าอื่นๆ
    header("Location: ../login_form.php");
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
        <?php include 'sidebar.php'; ?>

        <div class="flex-1 p-6 h-screen">
            <!-- Navbar -->
            <!-- Product Inventory Table -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">คลังสินค้า</h1>
                <div class="flex items-center space-x-4"> <!-- เพิ่ม space-x-4 เพื่อเพิ่มช่องว่าง -->

                    <form action="" method="GET">
                        <a href="create_product.php"
                            class=" bg-green-500 text-right text-white px-4 py-2 rounded-lg hover:bg-green-600 mr-4">
                            เพิ่มสินค้า
                        </a>
                        <input type="text" name="search" placeholder="ค้นหา..."
                            class="p-2 rounded-md border border-gray-300">
                    </form>
                </div>
            </div>
            <!-- <div class="flex justify-end items-end mb-6 ">
                <a href="create_product.php"
                    class=" bg-green-500 text-right text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    เพิ่มสินค้า
                </a>
            </div> -->

            <table class="min-w-full table-auto bg-white p-6 rounded-lg shadow-md  text-center">
                <thead class="text-center">
                    <tr>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700 ">รูปภาพ</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">ชื่อสินค้า</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">ราคา</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">ประเภท</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700">รายละเอียด</th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700"></th>
                        <th class="px-4 py-2 text-sm font-medium text-gray-700"></th>

                    </tr>
                </thead>
               
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <?php if (!empty($row['product_img'])): ?>
                                        <img src="../<?php echo htmlspecialchars($row['product_img']); ?>"
                                            class="w-20 h-20 object-cover">
                                    <?php else: ?>
                                        No image
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($row['product_name']); ?>
                                </td>

                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($row['product_price']); ?>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($row['product_type']); ?>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <?php echo htmlspecialchars($row['product_detail']); ?>
                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <button
                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                        onclick="window.location.href='edit_product.php?id=<?php echo htmlspecialchars($row['product_id']); ?>'">
                                        แก้ไขสินค้า
                                    </button>

                                </td>
                                <td class="px-4 py-2 text-sm text-gray-700">
                                    <button
                                        class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                        onclick="window.location.href='delete_product.php?id=<?php echo htmlspecialchars($row['product_id']); ?>'">
                                        ลบสินค้า
                                    </button>

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
</body>

</html>