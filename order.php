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
    SELECT orders.order_id, orders.total_price, orders.order_status, orders.order_date ,orders.payment_slip ,orders.order_code
    FROM orders
    WHERE orders.user_id = '$user_id'
    ORDER BY orders.order_date DESC
";

$result = mysqli_query($conn, $order_query);

if (!$result) {
    echo "เกิดข้อผิดพลาดในการดึงข้อมูลคำสั่งซื้อ: " . mysqli_error($conn);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่ามีการส่งค่า order_id มาจากฟอร์มเปลี่ยนสถานะหรือไม่
    if (isset($_POST['order_id']) && isset($_POST['action'])) {
        $order_id = $_POST['order_id'];
        $action = $_POST['action'];

        // กำหนดสถานะใหม่ตาม action ที่ส่งมา
        if ($action == 'confirm') {
            $new_status = 'Completed';
        } elseif ($action == 'cancel') {
            $new_status = 'Cancelled';
        } else {
            echo "การดำเนินการไม่ถูกต้อง";
            exit();
        }

        // ตรวจสอบคำสั่งซื้อที่เป็นของผู้ใช้ปัจจุบัน
        $order_query = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'";
        $order_result = mysqli_query($conn, $order_query);

        if (mysqli_num_rows($order_result) > 0) {
            // อัปเดตสถานะคำสั่งซื้อ
            $update_query = "UPDATE orders SET order_status = '$new_status' WHERE order_id = '$order_id'";
            if (!mysqli_query($conn, $update_query)) {
                echo "เกิดข้อผิดพลาดในการอัปเดตสถานะคำสั่งซื้อ: " . mysqli_error($conn);
                exit();
            }
        } else {
            echo "ไม่พบคำสั่งซื้อที่ตรงกับผู้ใช้ปัจจุบัน";
            exit();
        }

        // ตรวจสอบว่ามีการอัปโหลดไฟล์หลักฐานการโอนเงินหรือไม่
    } elseif (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == 0) {
        $order_id = $_POST['order_id'];

        // กำหนดโฟลเดอร์สำหรับจัดเก็บรูปภาพ
        $target_dir = "./payment_slip/";
        $file_name = uniqid() . basename($_FILES["payment_slip"]["name"]);
        $target_file = $target_dir . $file_name;

        // ย้ายไฟล์ไปยังโฟลเดอร์ payment_slip
        if (move_uploaded_file($_FILES["payment_slip"]["tmp_name"], $target_file)) {
            $img_path = "payment_slip/" . $file_name;
            $update_query = "UPDATE orders SET payment_slip = '$img_path', order_status = 'Payment Received' WHERE order_id = '$order_id'";

            if (!mysqli_query($conn, $update_query)) {
                echo "เกิดข้อผิดพลาดในการอัปเดตฐานข้อมูล: " . mysqli_error($conn);
                exit();
            }
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
            exit();
        }
    }
    header("Location: order.php");
    exit(); // ทำให้แน่ใจว่าไม่มียูโค้ดต่อจากนี้ที่ถูกดำเนินการ
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

    <div class="container mx-auto mt-10 relative overflow-x-auto">
        <h1 class="text-3xl font-bold mb-5 text-center">รายการคำสั่งซื้อของฉัน</h1>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                หมายเลขคำสั่งซื้อ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                วันที่
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ราคา
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                สถานะ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ดูรายละเอียด
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                หลักฐานการชำระเงิน
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['order_code']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['order_date']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo number_format($row['total_price'], 2); ?> บาท
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusText = '';
                                    $statusColor = '';

                                    switch ($row['order_status']) {
                                        case 'Order Placed':
                                            $statusText = 'รอชำระเงิน';
                                            $statusColor = 'bg-yellow-500 text-white';
                                            break;
                                        case 'Payment Received':
                                            $statusText = 'ชำระเงินแล้ว รอตรวจสอบ';
                                            $statusColor = 'bg-blue-500 text-white';
                                            break;
                                        case 'Order Processing':
                                            $statusText = 'กำลังจัดส่ง';
                                            $statusColor = 'bg-orange-500 text-white';
                                            break;
                                        case 'Completed':
                                            $statusText = 'จัดส่งสำเร็จ';
                                            $statusColor = 'bg-green-500 text-white';
                                            break;
                                        case 'Cancelled':
                                            $statusText = 'ยกเลิก';
                                            $statusColor = 'bg-red-500 text-white';
                                            break;
                                        default:
                                            $statusText = 'ไม่ทราบสถานะ';
                                            $statusColor = 'bg-gray-500 text-white';
                                            break;
                                    }
                                    ?>
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusColor; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="order_detail.php?order_id=<?php echo $row['order_id']; ?>"
                                        class="text-indigo-600  hover:text-indigo-900"><svg xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor"
                                            style="width: 36px;">
                                            <path strokeLinecap="round" strokeLinejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path strokeLinecap="round" strokeLinejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="flex px-6 py-4 whitespace-nowrap">
                                    <?php if (!empty($row['payment_slip'])): ?>
                                        <!-- หากมีสลิปจะแสดงรูปสลิปและปุ่มแก้ไข -->
                                        <img src="./<?php echo htmlspecialchars($row['payment_slip']); ?>" alt="Slip Image"
                                            class="w-24 h-24 rounded-md cursor-pointer" onclick="showFullScreenModal(this.src)" />
                                        <?php if ($row['order_status'] != 'Completed' && $row['order_status'] != 'Order Processing'): ?>
                                            <button onclick="openModal('<?php echo $row['order_id']; ?>')"
                                                class="h-10 text-blue px-4 py-2 mt-5 ml-5 rounded">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                                แก้ไขสลิป
                                            </button>
                                        <?php endif; ?>

                                    <?php else: ?>
                                        <!-- หากยังไม่มีสลิปจะแสดงปุ่มอัปโหลด -->
                                        <?php if ($row['order_status'] != 'Cancelled'): ?>
                                            <button onclick="openModal('<?php echo $row['order_id']; ?>')"
                                                class="bg-indigo-400 text-white px-2 rounded hover:bg-blue-700">
                                                อัปโหลดหลักฐานการโอนเงิน
                                            </button>
                                        <?php endif; ?>

                                        <?php if ($row['order_status'] == 'Order Placed'): ?>
                                            <form id="orderForm" method="POST" action="">
                                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">

                                                <button type="button" onclick="confirmAction('cancel')"
                                                    class="bg-red-400 text-white ml-5 px-4 py-2 rounded hover:bg-red-700">ยกเลิก</button>
                                                <input type="hidden" id="actionInput" name="action" value="">
                                            </form>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                    <!-- เงื่อนไขแสดงปุ่มยืนยันรับสินค้า หรือยกเลิก -->
                                    <?php if ($row['order_status'] == 'Order Processing'): ?>
                                        <form id="orderForm" class="p-5 m-2" method="POST" action="">
                                            <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                            <button type="button" onclick="confirmAction('confirm')"
                                                class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-700">ยืนยันรับสินค้า</button>
                                            <button type="button" onclick="confirmAction('cancel')"
                                                class="bg-red-400 text-white px-2 py-1 rounded hover:bg-red-700">ไม่รับสินค้า</button>
                                            <input type="hidden" id="actionInput" name="action" value="">
                                        </form>
                                    <?php endif; ?>
                                </td>


                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal สำหรับแสดงภาพแบบเต็มจอ -->
            <div id="fullScreenModal"
                class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 flex items-center justify-center z-50">
                <span class="absolute top-4 right-6 text-white text-3xl cursor-pointer"
                    onclick="closeFullScreenModal()">&times;</span>
                <img id="modalImage" class="max-w-full max-h-full rounded-lg" alt="Full Screen Slip">
            </div>

            <!-- Modal สำหรับแสดง QR Code และฟอร์มอัปโหลด -->
            <div id="paymentModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 hidden">
                <div class="bg-white rounded-lg shadow-lg p-6 w-80 md:w-96">
                    <h2 class="text-2xl font-bold mb-4">ชำระเงิน</h2>

                    <!-- QR Code -->
                    <div class="flex justify-center mb-4">
                        <img src="https://miro.medium.com/v2/resize:fit:789/1*A9YcoX1YxBUsTg7p-P6GBQ.png" alt="QR Code"
                            class="w-full h-full">
                    </div>

                    <p class="text-center text-gray-600 mb-4">สแกน QR Code เพื่อชำระเงิน</p>

                    <!-- ฟอร์มอัปโหลดหลักฐานการโอนเงิน -->
                    <form action="" method="POST" enctype="multipart/form-data" id="uploadForm">
                        <input type="hidden" name="order_id" id="order_id_input">

                        <input type="file" name="payment_slip" accept="image/*"
                            class="mt-2 mb-4 block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none">

                        <button type="submit" name="upload_slip"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 w-full">ส่งหลักฐานการโอนเงิน</button>

                    </form>

                    <!-- ปุ่มปิด Modal -->
                    <button onclick="closeModal()"
                        class="bg-red-500 text-white mt-5 px-4 py-2 rounded hover:bg-red-700 w-full">ปิด</button>
                </div>
            </div>

        <?php else: ?>
            <p class="text-center text-gray-500">ไม่มีคำสั่งซื้อในขณะนี้</p>
        <?php endif; ?>
    </div>

</body>


<script>
    // ฟังก์ชันแสดง Modal พร้อมภาพแบบเต็มจอ
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


    // ฟังก์ชันเปิด Modal และกำหนดค่า order_id ให้กับ input
    function openModal(order_id) {
        document.getElementById('order_id_input').value = order_id;
        document.getElementById('paymentModal').classList.remove('hidden');
    }

    // ฟังก์ชันปิด Modal
    function closeModal() {
        document.getElementById('paymentModal').classList.add('hidden');
    }

    function confirmAction(action) {
        // เรียกใช้ SweetAlert เพื่อแจ้งเตือน
        const actionText = action === 'confirm' ? 'ยืนยันรับสินค้า' : 'ยกเลิก';
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: `คุณต้องการ${actionText}ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'confirm' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ใช่',
            cancelButtonText: 'ไม่ใช่'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้ายืนยัน ให้ส่งฟอร์ม
                document.getElementById('actionInput').value = action;
                document.getElementById('orderForm').submit();
            }
        });
    }


</script>

</html>