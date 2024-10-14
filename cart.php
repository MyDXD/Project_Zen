<script src="https://cdn.tailwindcss.com"></script>


<?php
session_start(); // เรียกใช้ session_start() ที่จุดเริ่มต้น
include("dbconnect.php");


// คำนวณข้อมูลตะกร้าสินค้าจากฐานข้อมูล
$user_id = $_SESSION['user_id'];
$subtotal = 0;

if ($user_id) {
    $cart_query = "SELECT p.product_price, c.quantity FROM cart c JOIN products p ON c.product_id = p.product_id WHERE c.user_id = '$user_id'";
    $cart_result = mysqli_query($conn, $cart_query);

    while ($row = mysqli_fetch_assoc($cart_result)) {
        $subtotal += $row['product_price'] * $row['quantity'];
    }

    // คำนวณยอดรวมทั้งหมด
    $total = $subtotal;
}



// ตรวจสอบว่ามีการตั้งค่า user_id หรือไม่
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // ดึงข้อมูลสินค้าที่ผู้ใช้งานเพิ่มในตะกร้า
    $sql = "
        SELECT cart.product_id, cart.quantity, products.product_name, products.product_price, products.product_img 
        FROM cart 
        JOIN products ON cart.product_id = products.product_id 
        WHERE cart.user_id = $user_id
    ";

    $result = $conn->query($sql);
    $cart_items = [];

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $cart_items[] = $row;
            }
        } 
    } else {
        echo "เกิดข้อผิดพลาดในการดึงข้อมูล: " . $conn->error;
    }
} else {
    // กรณีที่ไม่มี user_id ใน session
    echo "กรุณาล็อกอินก่อนใช้งานตะกร้า";
}

// เมื่อกดปุ่มเช็คเอาท์
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // เพิ่มข้อมูลลงในตาราง orders
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'pending'; // กำหนดสถานะเริ่มต้น
    $delivery_address = 'Some address'; // สมมติให้มีที่อยู่จัดส่ง

    $order_query = "INSERT INTO orders (user_id, total_price, order_status, order_date, delivery_address) 
                    VALUES ('$user_id', '$total', '$order_status', '$order_date', '$delivery_address')";

    if (mysqli_query($conn, $order_query)) {
        // ดึง order_id ที่เพิ่งถูกสร้าง
        $order_id = mysqli_insert_id($conn);
    } else {
        echo "เกิดข้อผิดพลาดในการสร้างคำสั่งซื้อ: " . mysqli_error($conn);
        exit();
    }

    // เพิ่มรายการสินค้าลงในตาราง order_items
    foreach ($cart_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['product_price'];

        $order_items_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES ('$order_id', '$product_id', '$quantity', '$price')";

        if (!mysqli_query($conn, $order_items_query)) {
            echo "เกิดข้อผิดพลาดในการเพิ่มสินค้าใน order_items: " . mysqli_error($conn);
        }
    }

    // ลบสินค้าจากตะกร้า
    $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
    if (!mysqli_query($conn, $clear_cart_query)) {
        echo "เกิดข้อผิดพลาดในการเคลียร์ตะกร้า: " . mysqli_error($conn);
    }

    // แสดงข้อความสำเร็จและเปลี่ยนเส้นทางไปยังหน้าสรุปคำสั่งซื้อ
    echo '<script>
    window.onload = function() {
        Swal.fire({
            title: "<p style=\'font-size: 2rem; color: #555;\'>สำเร็จ!",
            html: "<p style=\'font-size: 1.5rem; color: #555;\'>การสั่งซื้อของคุณสำเร็จแล้ว!</p>",
            icon: "success",
            confirmButtonText: "ตกลง",
            customClass: {
            popup: "swal-wide",
            confirmButton: "swal-button"
        }
        }).then(function() {
                            window.location.href = "/order.php";
                        });
    };
</script>';
}

include "nav_bar.php"; // เรียกไฟล์ nav_bar.php
?>


<div class="bg-white">
    <div class="mx-auto max-w-2xl px-4 pb-24 pt-16 sm:px-6 lg:max-w-7xl lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Shopping Cart</h1>
        <form method="POST" class="mt-12 lg:grid lg:grid-cols-12 lg:items-start lg:gap-x-12 xl:gap-x-16">
            <section aria-labelledby="cart-heading" class="lg:col-span-7">
                <h2 id="cart-heading" class="sr-only">Items in your shopping cart</h2>

                <ul role="list" class="divide-y divide-gray-200 border-b border-t border-gray-200">
                    <?php foreach ($cart_items as $item): ?>
                    <li class="flex py-6 sm:py-10">
                        <div class="flex-shrink-0">
                            <img src="<?php echo $item['product_img']; ?>" alt="<?php echo $item['product_name']; ?>"
                                class="h-24 w-24 rounded-md object-cover object-center sm:h-48 sm:w-48">
                        </div>

                        <div class="ml-4 flex flex-1 flex-col justify-between sm:ml-6">
                            <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                <div>
                                    <div class="flex justify-between">
                                        <h3 class="text-sm">
                                            <a href="#"
                                                class="font-medium text-gray-700 hover:text-gray-800"><?php echo $item['product_name']; ?></a>
                                        </h3>
                                    </div>
                                    <p class="mt-1 text-sm font-medium text-gray-900">
                                        $<?php echo number_format($item['product_price'], 2); ?></p>
                                </div>

                                <div class="mt-4 sm:mt-0 sm:pr-9">
                                    <label for="quantity-<?php echo $item['product_id']; ?>" class="sr-only">Quantity,
                                        <?php echo $item['name']; ?></label>
                                    <select id="quantity-<?php echo $item['product_id']; ?>"
                                        name="quantity-<?php echo $item['product_id']; ?>"
                                        class="max-w-full rounded-md border border-gray-300 py-1.5 text-left text-base font-medium leading-5 text-gray-700 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                                        <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>"
                                            <?php if ($i == $item['quantity']) echo 'selected'; ?>><?php echo $i; ?>
                                        </option>
                                        <?php endfor; ?>
                                    </select>

                                    <div class="absolute right-0 top-0">
                                        <button type="button"
                                            class="-m-2 inline-flex p-2 text-gray-400 hover:text-gray-500">
                                            <span class="sr-only">Remove</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                aria-hidden="true">
                                                <path
                                                    d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <section aria-labelledby="summary-heading"
                class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>

                <dl class="mt-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Subtotal</dt>
                        <dd class="text-sm font-medium text-gray-900"><?php echo number_format($subtotal, 2); ?> บาท
                        </dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <dt class="text-base font-medium text-gray-900">Order total</dt>
                        <dd class="text-base font-medium text-gray-900"><?php echo number_format($total, 2); ?> บาท</dd>
                    </div>
                </dl>
                <?php
                    // ตรวจสอบว่ามีสินค้ามากกว่า 0 รายการในตะกร้าหรือไม่
                    if (count($cart_items) > 0) {
                        echo '
                        <form method="POST">
                            <div class="mt-6">
                                <button type="submit"
                                    class="w-full rounded-md border border-transparent bg-indigo-600 px-4 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50">Checkout</button>
                            </div>
                        </form>
                        ';
                    } else {
                        echo '
                        <div class="mt-6">
                            <button type="button" disabled
                                class="w-full rounded-md border border-transparent bg-gray-400 px-4 py-3 text-base font-medium text-white shadow-sm cursor-not-allowed">Checkout</button>
                            <p class="text-red-500 text-sm mt-2">ไม่มีสินค้าในตะกร้า</p>
                        </div>
                        ';
                    }
                    ?>
        </form>
        </section>
    </div>
</div>