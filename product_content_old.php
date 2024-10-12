<!DOCTYPE html>
<html lang="en">
<?php
include "dbconnect.php";
include "nav_bar.php";
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสินค้า</title>
    <style>
        .content_container {
            margin: 5% 25%;
            /* text-align: center; */
            position: relative;
        }

        .input_align {
            width: 50%;
            margin: 5px;
            padding: 5px;
        }

        .content_right {
            width: 50%;
            height: 100%;
            right: 0%;
            position: absolute;
        }

        .product_pic {
            top: 5%;
            left: 5%;
            position: absolute;
            width: 250px;
            height: 250px;
            object-fit: cover;
        }

        .product_price {
            margin-top: 10%;

        }

        .product_buy_botton {
            margin-bottom: 5%;
            position: absolute;
            background-color: lightblue;
            padding: 10px;
            border-radius: 10%;
        }
    </style>
</head>
<?php
if ($id = isset($_GET['id']) ? $_GET['id'] : null) {

    $query = mysqli_query($conn, "SELECT * FROM `products` WHERE product_id=$id");
    $row = mysqli_fetch_assoc($query);
    $productname = $row['product_name'];
    $productinfo = $row['product_detail'];
    $productprice = $row['product_price'];
}
?>

<body>
    <div class="content_container">
        <div class="content_container">
            <img src=" <?= htmlspecialchars($row['product_img']) ?>">

            <div class="content_right">
                <h2 class="product_name"><?php echo "$productname" ?></h2>
                <p class=""><?php echo "$productinfo" ?></p>
                <p class="product_price">ราคา <?php echo "$productprice" ?> บาท</p>
                <p><a href="#" class="product_buy_botton">สั่งซื้อสินค้า</a></p>
            </div>

        </div>
</body>

</html>