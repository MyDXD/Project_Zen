<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body>
    <?php include "nav_bar.php";?>
    <div class="content_container" style="margin-left:20%; margin-right:20%; margin-top:30px; text-align:center;">
        <div class="signupcontent" style="text-align:center; width: 50%; right: 0px;">
            <h1 style="text-align:center">กรุณากรอกรหัสของบัญชีนี้อีกครั้งเพื่อความปลอดภัยของท่าน</h1>
                <form action="user_edit_check.php" style="text-align:center" method="post">
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">รหัสผ่านของท่าน</h3>
                    <input type="password" name="password" style="Width:75%; height:13px; padding:8px;"><br>
                    <p><input type="submit" value="ยืนยัน" style="Width:20%; height:25px;" name="check"></p>
                </form>
        </div>
    </div>
    
</body>
</html>