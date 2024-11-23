<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าแนะนำ</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <?php include 'dbconnect.php'; // เชื่อมต่อฐานข้อมูล ?>
    <?php include 'nav_bar.php'; // เมนูนำทาง ?>

    <div class="container mx-auto bg-white">
        <div class="md:flex md:items-center md:justify-between">
            <!-- แบบฟอร์มค้นหา -->

        </div>

        <div
            class="mx-auto bg-gray-100 rounded-lg shadow-md mt-2 max-w-2xl px-4 py-16 sm:px-6 sm:py-16 lg:max-w-7xl lg:px-8">
            <h2 class="text-2xl font-bold tracking-tight text-center text-gray-900">สินค้าทั้งหมด</h2>
            <form action="" method="POST" class="flex items-center w-full max-w-md mx-auto p-4 space-x-2">
                <input type="text" name="search" placeholder="ค้นหาสินค้า"
                    class="border border-gray-300 p-2 rounded-lg w-full">

                <!-- เพิ่มส่วนการเลือกประเภทสินค้า -->
                <select name="product_type" class="border border-gray-300 p-2 rounded-lg">
                    <option value="">ทุกประเภท</option>

                    <?php
                    // ดึงข้อมูลประเภทสินค้าแบบไม่ซ้ำจากฐานข้อมูล
                    $type_query = "SELECT DISTINCT product_type FROM products WHERE product_type IS NOT NULL";
                    $type_result = $conn->query($type_query);

                    if ($type_result->num_rows > 0) {
                        // แสดงประเภทสินค้าใน <option>
                        while ($type_row = $type_result->fetch_assoc()) {
                            $type = htmlspecialchars($type_row['product_type']);
                            echo "<option value=\"$type\">$type</option>";
                        }
                    }
                    ?>
                </select>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">ค้นหา</button>
            </form>

            <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 md:grid-cols-4 lg:gap-x-8">
                <?php
                // รับค่าการค้นหาและประเภทสินค้า
                $search = isset($_POST['search']) ? $_POST['search'] : '';
                $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';

                // สร้างคำสั่ง SQL ที่สามารถกรองตามชื่อสินค้าและประเภทสินค้าได้
                $sql = "SELECT * FROM products WHERE 1=1";

                // เพิ่มเงื่อนไขการค้นหาชื่อสินค้า หากมีการกรอกคำค้นหา
                if ($search) {
                    $sql .= " AND product_name LIKE '%$search%'";
                }

                // เพิ่มเงื่อนไขการกรองประเภทสินค้า หากมีการเลือกประเภท
                if ($product_type) {
                    $sql .= " AND product_type = '$product_type'";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["product_id"];
                        ?>
                        <div class="group relative bg-white shadow-md rounded-md p-4">
                            <div class="h-56 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75">
                                <img src="<?= htmlspecialchars($row['product_img']) ?>"
                                    alt="<?= htmlspecialchars($row['product_name']) ?>"
                                    class="h-full w-full object-cover object-center">
                            </div>
                            <h3 class="mt-4 text-sm text-gray-700">
                                <a href="product_content.php?id=<?= urlencode($id) ?>">ชื่อสินค้า :
                                    <span class="absolute inset-0"></span>
                                    <?= htmlspecialchars($row['product_name']) ?>
                                </a>
                            </h3>
                            <p class="mt-1 text-sm font-medium text-gray-900">ราคา :
                                <?= number_format($row['product_price'], 2) ?> บาท
                            </p>
                            <p class="mt-1 text-sm font-medium text-gray-900">คงเหลือ :
                                <?= number_format($row['stock']) ?> ชิ้น
                            </p>
                            <p class="mt-1 text-sm text-gray-500">ประเภท : <?= htmlspecialchars($row['product_type']) ?></p>
                            <p class="mt-1 text-sm text-gray-500"><?= htmlspecialchars($row['product_detail']) ?></p>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="text-gray-500">ไม่พบสินค้า</p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>