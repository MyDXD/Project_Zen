<?php
include "dbconnect.php";
$db = new mysqli("localhost", "root", "", "sanahstore");
if (isset($_POST["submit"])) {
    $product_info = $_POST["product_info"];
    $product_price = $_POST["product_price"];
    $imgname = mysqli_real_escape_string($db, $_FILES["product_pic"]["name"]);
    $imgdata = mysqli_real_escape_string($db, file_get_contents($_FILES["product_pic"]["tmp_name"]));
    $imgtype = mysqli_real_escape_string($db, $_FILES["product_pic"]["type"]);

    if (substr($imgtype, 0, 5) == "image") {
        $query = "  INSERT INTO `productdata`(product_name, product_pic,product_info,price) VALUES ('$imgname','$imgdata','$product_info','$product_price')";
      
        if (mysqli_query($db, $query)) {
            echo "เพิ่มข้อมูลสำเร็จ";
        } else {
            echo "บัคฮะแก้ที" . mysqli_error($db);
        }
    } else {
        echo "ไม่สามารถเพิ่มข้อมูลได้";
    }
}


?>

