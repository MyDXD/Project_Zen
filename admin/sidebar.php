<head>
    <!-- นำเข้าไลบรารี SweetAlert จาก CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div class="flex h-screen">
    <div class="w-64 h-full bg-gray-800 p-6">
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
            <li class="mb-4">
                <a href="#" class="text-red-300 hover:text-white" onclick="confirmLogout()">ออกจากระบบ</a>
            </li>
        </ul>
    </div>
</div>

<script>
// ฟังก์ชันสำหรับการยืนยันการออกจากระบบ
function confirmLogout() {
    // เรียกใช้ SweetAlert สำหรับการยืนยัน
    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณต้องการออกจากระบบใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ออกจากระบบ!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // ถ้าผู้ใช้กดยืนยัน ให้นำไปยังหน้า logout.php
            window.location.href = "../logout.php";
        }
    })
}
</script>