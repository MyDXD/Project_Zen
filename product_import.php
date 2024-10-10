<!DOCTYPE html>
<html lang="th">
<?php include "nav_bar.php"?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าขนมนำเข้า</title>
    <style>
        .product_container {
            display: flex;
            flex-wrap: wrap;
            justify-content: start;
            column-gap: 18px;
            text-align: left;
        }

        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 100%;
            max-width: 200px;
            max-height: 320px;
            margin: 20px;
            padding: 16px;
            text-align: center;
            align-items: center;
        }

        .product-card img {
            max-width: 250px;
            border-radius: 8px;
            height: auto;
        }

        .product-card h2 {
            font-size: 1.5em;
            margin: 10px 0;
        }

        .product-card p {
            color: #666;
            font-size: 1em;
            margin: 10px 0;
        }

        .product-card .price {
            color: #e74c3c;
            font-size: 1.2em;
            margin: 10px 0;
        }

        .product-card .button {
            background-color: #3498db;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 1em;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .product-card .button:hover {
            background-color: #2980b9;
        }

        .product-card img {
            width: 100%;
            height: 100%;
            max-width: 300px;
            max-height: 200px;
            object-fit: cover;
        }

        svg {
            background: green;
            color: #000;
            padding: 10px;
            position: relative;
            top: 13px;
        }
    </style>
</head>

<body>
    <?php
    $db = new mysqli("localhost", "root", "", "sanahstore");
    ?>
    <h2 style="text-align: center;">สินค้าขนมนำเข้า</h2>
    <div class="product_container">
        
        <!-- <div class="product-card"> -->
        <?php
        if (isset($_POST['search'])) {
            $search = isset($_POST['search']) ? $_POST['search'] : '';
            $sql = "SELECT * FROM `productdata` WHERE `product_type` = 'ขนมนำเข้า' AND `product_name` LIKE '%$search%'";
        } else {
            $sql = "SELECT * FROM `productdata` WHERE `product_type` = 'ขนมนำเข้า'";
        }
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["product_id"];
                echo '<div class="product-card">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['product_pic']) . '" alt="Product Image" />';
                echo '<h2>' . htmlspecialchars($row['product_name']) . '</h2>';
                echo "$row[price] บาท";
                echo '<br><a class="button" href="product_content.php?id=' . urlencode($id) . '">รายละเอียดสินค้า  </a> <a href=""><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
</svg></a>';
                echo '</div>';
            }
        ?>
        <?php }else{
            echo"<h1>ไม่พบสินค้า</h1>";
        }  ?>

    </div>
</body>

</html>