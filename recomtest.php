<style>
.product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .product-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 300px;
            margin: 20px;
            padding: 16px;
            text-align: center;
        }
        .product-card img {
            max-width: 100%;
            border-radius: 8px;
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
</style>
<?php
    include "dbconnect.php";
    $sql="SELECT * FROM `productdata`";
    $product=$conn->query($sql);
?>                
                
                <?php while($row = mysqli_fetch_assoc($product)){?>
                    <?php $pic="product_pic/".$row['product_pic']?>
                <div class="product-card">
                    <img src="<?=$pic?>" alt="Product Image">
                    <h2><?php echo $row["product_name"];?></h2>
                    <p><?php echo $row["product_info"];?></p>
                    <div class="price"><?php echo $row["price"];?></div>
                    <a href="#" class="button">ซื้อเลย</a>
                </div>
                <?php } ?>