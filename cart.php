<script src="https://cdn.tailwindcss.com"></script>

<?php
session_start();
include("dbconnect.php");


// ตรวจสอบว่ามีการตั้งค่า user_id หรือไม่
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $subtotal = 0;


    // ดึงที่อยู่การจัดส่งจากตาราง userdata
    $address_query = "SELECT LEFT(address, 256) AS delivery_address FROM userdata WHERE user_id = '$user_id'";
    $address_result = mysqli_query($conn, $address_query);

    if ($address_result && mysqli_num_rows($address_result) > 0) {
        $address_row = mysqli_fetch_assoc($address_result);
        $delivery_address = $address_row['delivery_address']; // ที่อยู่จากฐานข้อมูล
    } else {
        // หากไม่มีที่อยู่การจัดส่งให้ใช้ค่าที่กำหนดไว้ล่วงหน้า
        $delivery_address = 'ไม่พบที่อยู่การจัดส่ง';
    }

    // Query ตะกร้าสินค้าของผู้ใช้
    $cart_query = "SELECT p.product_price, c.quantity FROM cart c 
                   JOIN products p ON c.product_id = p.product_id 
                   WHERE c.user_id = '$user_id'";
    $cart_result = mysqli_query($conn, $cart_query);

    while ($row = mysqli_fetch_assoc($cart_result)) {
        // ถ้า quantity เป็น 0 (ในกรณีไม่มีการตั้งค่า) จะใช้ค่า 1 แทน
        $quantity = $row['quantity'] ? $row['quantity'] : 1;

        // คำนวณผลรวมย่อยต่อชิ้น
        $subtotal += $row['product_price'] * $quantity;
    }

    // คำนวณยอดรวมทั้งหมด
    $total = $subtotal;

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


// ตรวจสอบว่ามีการส่งค่า remove_product_id หรือไม่
if (isset($_GET['remove_product_id'])) {
    $product_id = $_GET['remove_product_id'];

    // ลบสินค้าออกจากตาราง cart ของฐานข้อมูล
    $delete_query = "DELETE FROM cart WHERE product_id = '$product_id' AND user_id = '$user_id'";
    $delete_result = mysqli_query($conn, $delete_query);

    // ตรวจสอบว่าการลบสำเร็จหรือไม่
    if ($delete_result) {
        // ถ้าลบสำเร็จ Redirect กลับไปยังหน้าเดิม
        header("Location: cart.php");
        exit();
    } else {
        echo "ไม่สามารถลบสินค้าได้";
    }
}

// เมื่อกดปุ่มเช็คเอาท์
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // เพิ่มข้อมูลลงในตาราง orders
    $order_date = date('Y-m-d H:i:s');
    $order_code = 'ORDER_' . uniqid() . mt_rand(100, 999);
    $order_status = 'Order Placed'; // กำหนดสถานะเริ่มต้น
    $delivery_address = mysqli_real_escape_string($conn, $delivery_address);
    $total_price = $_POST['total_price'];

    // เพิ่มคำสั่งซื้อในตาราง orders พร้อมที่อยู่การจัดส่ง
    $order_query = "INSERT INTO orders (order_code,user_id, total_price, order_status, order_date, delivery_address) 
                    VALUES ('$order_code','$user_id', '$total_price', '$order_status', '$order_date', '$delivery_address')";

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

        // ดึงค่าจำนวนสินค้าที่เลือกจากฟอร์ม
        if (isset($_POST['quantity_' . $product_id])) {
            $quantity = $_POST['quantity_' . $product_id];
        } else {
            $quantity = 1; // ถ้าไม่มีการส่งค่าใช้ค่าเริ่มต้นเป็น 1
        }

        // ตรวจสอบจำนวนสินค้าที่มีในคลังก่อน
        $product_query = "SELECT stock FROM products WHERE product_id = '$product_id'";
        $product_result = mysqli_query($conn, $product_query);

        // เพิ่มสินค้าในตาราง order_items
        $order_items_query = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                              VALUES ('$order_id', '$product_id', '$quantity', '$price')";

        if (!mysqli_query($conn, $order_items_query)) {
            echo "เกิดข้อผิดพลาดในการเพิ่มสินค้าใน order_items: " . mysqli_error($conn);
            exit();
        } else {
            // ลดจำนวนสินค้าจากตาราง products
            $update_product_query = "UPDATE products SET stock = stock - $quantity WHERE product_id = '$product_id'";

            if (!mysqli_query($conn, $update_product_query)) {
                echo "เกิดข้อผิดพลาดในการลดจำนวนสินค้า: " . mysqli_error($conn);
                exit();
            }
        }
    }

    // ลบสินค้าจากตะกร้า
    $clear_cart_query = "DELETE FROM cart WHERE user_id = '$user_id'";
    if (!mysqli_query($conn, $clear_cart_query)) {
        echo "เกิดข้อผิดพลาดในการเคลียร์ตะกร้า: " . mysqli_error($conn);
        exit();
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
                            window.location.href = "./order.php";
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
                        <li <?php echo $item['product_id'] ?> class="flex py-6 sm:py-10">
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
                                            ราคาต่อชิ้น <?php echo number_format($item['product_price'], 2); ?> บาท</p>
                                    </div>

                                    <?php
                                    // ดึงจำนวนสินค้าที่มีในคลังจากฐานข้อมูล
                                    $product_id = $item['product_id'];
                                    $product_price = $item['product_price'];
                                    $product_query = "SELECT stock FROM products WHERE product_id = '$product_id'";
                                    $product_result = mysqli_query($conn, $product_query);

                                    if ($product_result && $product_row = mysqli_fetch_assoc($product_result)) {
                                        $stock = $product_row['stock'];
                                    } else {
                                        $stock = 0; // กำหนดค่าเป็น 0 ถ้าหาข้อมูลสินค้าไม่ได้
                                    }
                                    ?>
                                    <span>
                                        <!-- Input สำหรับใส่จำนวนสินค้า โดยมีการจำกัด max ตามจำนวนในคลัง -->
                                        <input class="w-8 text-center border-1 quantity-input" type="number"
                                            name="quantity_<?php echo $product_id; ?>" min="1" max="<?php echo $stock; ?>"
                                            data-price="<?php echo $product_price; ?>"
                                            value="<?php echo $item['quantity'] ? $item['quantity'] : 1; ?>" />

                                        <!-- ปุ่มลบสินค้า ที่จะถูกเรียกใช้ด้วย JavaScript -->
                                        <button type="button" class="mt-2 text-sm text-red-500 hover:text-red-700"
                                            onclick="confirmRemove(<?php echo $item['product_id']; ?>)">
                                            ลบสินค้า
                                        </button>
                                    </span>

                                    <p class="mt-1 text-sm text-gray-700">สินค้าคงเหลือ: <?php echo $stock; ?> ชิ้น</p>
                                </div>
                            </div>

                        </li>
                    <?php endforeach; ?>

                </ul>
            </section>
            <section aria-labelledby="summary-heading"
                class="mt-16 rounded-lg bg-gray-50 px-4 py-6 sm:p-6 lg:col-span-5 lg:mt-0 lg:p-8">
                <h2 id="summary-heading" class="text-lg font-medium text-gray-900">Order summary</h2>

                <div class="mt-6 space-y-4">
                    <!-- แสดงผลรวมของการสั่งซื้อ -->
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                            <div class="text-base font-medium text-gray-900">Order total</div>
                            <div id="order-total" class="text-base font-medium text-gray-900">0 บาท</div>
                        </div>
                    </div>
                    <!-- Hidden input field สำหรับเก็บยอดรวม -->
                    <input type="hidden" name="total_price" id="hidden-total" value="0" />

                    <div class=" items-center border-t border-gray-200 pt-4">
                        <dt class="text-base font-medium text-gray-900">ที่อยู่การจัดส่ง</dt>
                        <dd class="text-base font-medium text-gray-900">
                            <?php echo isset($delivery_address) ? $delivery_address : 'ไม่พบที่อยู่'; ?>
                        </dd>
                    </div>
                </div>

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

<!-- JavaScript สำหรับคำนวณราคารวม -->
<script>
    // ฟังก์ชันสำหรับยืนยันการลบสินค้าก่อนการลบ
    function confirmRemove(productId) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการลบสินค้านี้ออกจากตะกร้า!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบออก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้ายืนยันให้ลบสินค้า
                window.location.href = "cart.php?remove_product_id=" + productId; // ส่งข้อมูลไปที่ cart.php
            }
        });
    }
    // ฟังก์ชันสำหรับคำนวณยอดรวมใหม่
    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.quantity-input').forEach(function (input) {
            let quantity = parseInt(input.value);
            let price = parseFloat(input.getAttribute('data-price'));
            total += quantity * price;
        });
        // แสดงยอดรวมใหม่
        document.getElementById('order-total').textContent = total.toLocaleString('th-TH', {
            style: 'currency',
            currency: 'THB'
        }) + ' บาท';
        // อัปเดตค่าของ hidden input field
        document.getElementById('hidden-total').value = total;
    }

    // ตรวจสอบเมื่อมีการเปลี่ยนแปลงจำนวนสินค้า
    document.querySelectorAll('.quantity-input').forEach(function (input) {
        input.addEventListener('input', calculateTotal);
    });

    // คำนวณยอดรวมเมื่อเริ่มโหลดหน้า
    window.addEventListener('load', calculateTotal);
</script>