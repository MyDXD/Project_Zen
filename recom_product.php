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

    <div class="container mx-auto bg-white">
        <div class="mx-auto bg-gray-100 rounded-lg shadow-md mt-2 max-w-2xl px-4 py-16 sm:px-6 sm:py-16 lg:max-w-7xl lg:px-8">
            <div class="md:flex md:items-center md:justify-between">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">สินค้าแนะนำ</h2>

            </div>

            <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-10 sm:grid-cols-3 md:grid-cols-4 lg:gap-x-8">
                <?php
                // แสดงรายการสินค้า
                $search = isset($_POST['search']) ? $_POST['search'] : '';
                $sql = $search ? "SELECT * FROM products WHERE product_name LIKE '%$search%'" : "SELECT * FROM products";
                $result = $conn->query(query: $sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = $row["product_id"];
                        ?>
                        <div class="group relative bg-white shadow-md rounded-md p-4">
                            <div class="h-56 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75">
                                <img src=" <?= htmlspecialchars($row['product_img']) ?>"
                                    alt="<?= htmlspecialchars($row['product_name']) ?>"
                                    class="h-full w-full object-cover object-center">
                            </div>
                            <h3 class="mt-4 text-sm text-gray-700">
                                <a href="product_content.php?id=<?= urlencode(string: $id) ?>">ชื่อสินค้า :
                                    <span class="absolute inset-0"></span>
                                    <?= htmlspecialchars(string: $row['product_name']) ?>
                                </a>
                            </h3>
                            <p class="mt-1 text-sm font-medium text-gray-900">ราคา :
                                <?= number_format($row['product_price'], 2) ?> บาท</p>
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