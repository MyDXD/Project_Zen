<?php
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
                <h1 class="text-2xl font-semibold">Dashboard</h1>
                <div class="flex items-center">
                    <input type="text" placeholder="ค้นหา..." class="p-2 rounded-md border border-gray-300 mr-4">
                </div>
            </div>
            

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">ยอดขายรวม</h3>
                    <p class="text-3xl font-bold">10,000 บาท</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">สินค้าในสต็อก</h3>
                    <p class="text-3xl font-bold">40</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">คำสั่งซื้อที่รอดำเนินการ</h3>
                    <p class="text-3xl font-bold">0</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">ร้าน</h3>
                    <p class="text-3xl font-bold">สานะกิมหยงออนไลน์</p>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-lg font-semibold mb-4">คำสั่งซื้อล่าสุด</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ออเดอร์ที่</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ชื่อลูกค้า</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">จำนวนเงินทั้งหมด</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">สถานะ</th>
                        </tr>
                    </thead>

                </table>
            </div>

            <!-- Product Inventory Table -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">รายการสินค้า</h3>
                <table class="min-w-full table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">รหัสสินค้า</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ชื่อสินค้า</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">คลังสินค้า</th>
                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">ราตา</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</body>

</html>