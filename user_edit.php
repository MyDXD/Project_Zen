<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>แก้ไขข้อมูล</title>
</head>
<?php include 'nav_bar.php';
include("dbconnect.php");
if(isset($_SESSION['email'])){
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM `userdata` WHERE `email`='$email'");

    if (!$query) {
        echo "Error: " . mysqli_error($conn);
    } else {
        if ($row = mysqli_fetch_assoc($query)) {
            $firstname=$row['first_name'];
            $lastname=$row['last_name'];
            $email=$row['email'];
            $phone_number=$row['phone_number'];
            $password=$row['password'];
            $address=$row['address'];
            $user_id =$row['user_id'];
        }
    }
}
?>

<?php
if(isset($_POST['submit'])){
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $update = "UPDATE `userdata` SET `email`='$email', `password`='$password', `first_name`='$firstname', `last_name`='$lastname', `address`='$address', `phone_number`='$phone' WHERE user_id=$user_id";
    if($conn->query($update) == TRUE){
        echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "<p style=\'font-size: 2rem; color: #555;\'>แก้ไขข้อมูลสำเร็จ",
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            customClass: {
                            popup: "swal-wide",
                            confirmButton: "swal-button"
                        }
                            }).then(function() {
                            window.location.href = "index.php";
                        });
                    };
                    </script>';
    }else {
        echo"บัคฮะแก้ที";
    }
    }
?>
<style>
     .editform input{
        margin: 5px;
     }
</style>
<body>
    <div class="content_container" style="margin-left:20%; margin-right:20%; margin-top:30px; text-align:center;">
        <div class="signupcontent" style="text-align:center;">
            <h1 style="text-align:center">แก้ไขข้อมูล</h1><br>
                <form action="" method="post" style="text-align:center" class="editform">
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">ชื่อจริง</h3>
                    <input type="text" name="first_name" style="Width:75%; height:13px; padding:8px;" value="<?php echo $firstname;?>"><br>
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">นามสกุล</h3>
                    <input type="text" name="last_name" style="Width:75%; height:13px; padding:8px;" value="<?php echo $lastname;?>"><br>
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">อีเมล</h3>
                    <input type="email" name="email" style="Width:75%; height:13px; padding:8px;" value="<?php echo $email;?>"><br>
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">รหัสผ่าน</h3>
                    <input type="text" name="password" style="Width:75%; height:13px; padding:8px;" value="<?php echo $password;?>"><br>
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">เบอร์โทรศัพท์</h3>
                    <input type="text" name="phone" style="Width:75%; height:13px; padding:8px;" value="<?php echo $phone_number;?>"><br>
                    <h3 style="padding-bottom:0px; margin-bottom:0px;">ที่อยู่สำหรับจัดส่ง</h3>
                    <input type="text" name="address" style="Width:75%; height:200px; padding:8px;" value="<?php echo $address;?>"><br>
                    <p><input type="submit" value="ยืนยัน" style="Width:20%; height:25px;" name="submit"></p>
                </form>
        </div>
    </div>
</body>
</html>