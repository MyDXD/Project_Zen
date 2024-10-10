<style>
    .container{
        margin: 5% 10%;
        text-align: center;
    }
    .input_align{
        width: 50%;
        margin: 5px;
        padding: 5px;
    }
</style>
<!DOCTYPE html>
<html>
<body>
<div class="container">
<form action="product_edit.php" method="post" enctype="multipart/form-data">
    <h1>เพิ่มข้อมูลสินค้า</h1>
    กรอกชื่อสินค้า<br>
    <input type="text" name="product_name" class="input_align"><br><br>
    กรอกรายละเอียดสินค้า<br>
    <textarea name="product_info" class="input_align" style="height: 250px;"></textarea><br><br>
    กรอกราคา<br>
    <input type="text" name="product_price" class="input_align"><br><br>
    เลือกไฟล์ภาพ<br>
    <input type="file" name="product_pic" id="fileToUpload" class="input_align"><br><br>
    <input type="submit" value="เพิ่มข้อมูล" name="submit" class="input_align"><br>
</form>
</div>

</body>
</html>