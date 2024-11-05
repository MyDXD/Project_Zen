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
    <div class="flex w-full h-screen"> <!-- ตั้งค่าความสูงเต็มหน้าจอ -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6 overflow-y-auto h-screen"> <!-- overflow ของส่วน content -->
            <!-- Navbar -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold">ข้อมูลผู้ใช้ในระบบ</h1>
                <div class="flex items-center space-x-4">

                    <form action="" method="GET">
                        <a href="create_customer.php"
                            class=" bg-green-500 text-right text-white px-4 py-2 rounded-lg hover:bg-green-600 mr-4">
                            เพิ่มผู้ใช้
                        </a>
                        <input type="text" name="search" placeholder="ค้นหา..."
                            class="p-2 rounded-md border border-gray-300">
                    </form>
                </div>
            </div>


            <div class="bg-white p-6 rounded-lg shadow-md">

                <h3 class="text-lg font-semibold mb-4">ข้อมูลลูกค้า</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>

                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">email</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ชื่อ</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">นามสกุล</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ที่อยู่</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">เบอร์โทร</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">บทบาท</th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-700"></th>
                            <th class="px-4 py-2 text-sm font-medium text-gray-700"></th>
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
                                        <?php echo htmlspecialchars($row['address']); ?>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['phone_number']); ?>
                                    </td>

                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <?php echo htmlspecialchars($row['role']); ?>
                                    </td>

                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <button
                                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                            onclick="window.location.href='edit_customer.php?id=<?php echo htmlspecialchars($row['user_id']); ?>'">
                                            แก้ไข
                                        </button>

                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700">
                                        <button
                                            class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                                            onclick="window.location.href='delete_customer.php?id=<?php echo htmlspecialchars($row['user_id']); ?>'">
                                            ลบ
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
    </div>
</body>

</html>