<!DOCTYPE html>
<html lang="en">
<?php
include "dbconnect.php";
include "nav_bar.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>รายละเอียดสินค้า</title>
</head>
<?php
if ($id = isset($_GET['id']) ? $_GET['id'] : null) {

    $query = mysqli_query($conn, "SELECT * FROM `products` WHERE product_id=$id");
    $row = mysqli_fetch_assoc($query);
    $productname = $row['product_name'];
    $productdetail = $row['product_detail'];
    $productprice = $row['product_price'];
    $producttype = $row['product_type'];

}
?>

<body>
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl bg-gray-100 mt-2 rounded-lg shadow-md px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:items-start lg:gap-x-8">
                <div class="flex flex-col-reverse">


                    <div class="aspect-h-1 aspect-w-1 w-full">
                        <!-- Tab panel, show/hide based on tab state. -->
                        <div id="tabs-1-panel-1" aria-labelledby="tabs-1-tab-1" role="tabpanel" tabindex="0">
                            <img src="<?= htmlspecialchars($row['product_img']) ?>"
                                alt="Angled front view with bag zipped and handles upright."
                                class="h-2/3 w-2/3 m-16 px-4 sm:mt-16 sm:px-0 lg:mt-0 object-cover object-center sm:rounded-lg">
                        </div>
                    </div>
                </div>

                <div class="m-16 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900"><?php echo "$productname" ?></h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-3xl tracking-tight text-gray-900">ราคา : <?php echo "$productprice" ?> บาท</p>
                    </div>
                    <div class="mt-3">
                        <h2 class="sr-only">Product information</h2>
                        <p class="text-xl tracking-tight text-gray-900">ประเภท : <?php echo "$producttype" ?></p>

                    </div>



                    <div class="mt-6">
                        <div class="space-y-6 text-base text-gray-700">
                            <p><?php echo "$productdetail" ?></p>
                        </div>
                    </div>

                    <form class="mt-6">
                        <div class="mt-10 flex">
                            <button type="submit"
                                class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-indigo-600 px-8 py-3 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full">Add
                                to bag</button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</body>

</html>