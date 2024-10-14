<?php
include "dbconnect.php";
include "login_form.php";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    // $password = md5($password); // หากคุณใช้การเข้ารหัส

    $sql = "SELECT * FROM `userdata` WHERE `email`='$email' AND `password`='$password'";
    $check = $conn->query($sql);

    if ($check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['user_id']; // เพิ่มการตั้งค่า user_id

        if ($_SESSION['role'] === 'admin') {
            echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "<p style=\'font-size: 2rem; color: #555;\'>เข้าสู่ระบบสำเร็จ",
                            html: "<p style=\'font-size: 1.5rem; color: #555;\'>ยินดีต้อนรับ Admin</p>",
                            icon: "success",
                            confirmButtonText: "ตกลง",
                            customClass: {
                            popup: "swal-wide",
                            confirmButton: "swal-button"
                        }
                        }).then(function() {
                            window.location.href = "admin/dashboard.php";
                        });
                    };
                </script>';
            exit();
        } elseif ($_SESSION['role'] === 'user') {
            echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "<p style=\'font-size: 2rem; color: #555;\'>เข้าสู่ระบบสำเร็จ",
                            html: "<p style=\'font-size: 1.5rem; color: #555;\'>ยินดีต้อนรับ</p>",
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
            exit();
        }
    } else {
        echo '<script>
                window.onload = function() {
                    Swal.fire({
                        title: "<p style=\'font-size: 2rem; color: #555;\'>ไม่สามารถเข้าสู่ระบบได้",
                        html: "<p style=\'font-size: 1.5rem; color: #555;\'>ชื่อหรือรหัสผ่านอาจไม่ถูกต้อง</p>",
                        icon: "error",
                        confirmButtonText: "ตกลง",
                        customClass: {
                        popup: "swal-wide",
                        confirmButton: "swal-button"
                    }
                    }).then(function() {
                        window.location.href = "login_form.php";
                    });
                };
            </script>';
        exit();
    }
}
?>
