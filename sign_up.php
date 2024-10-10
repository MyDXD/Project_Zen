<?php
include "dbconnect.php";
include "sign_upform.php";
if (isset($_POST['sign_up'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstname = $_POST['first_name'];
    $lastname = $_POST['last_name'];
    $passwordcheck = $_POST['passwordcheck'];

    $checkemail = "SELECT * FROM userdata WHERE email='$email'";
    $check = $conn->query($checkemail);
    if ($check->num_rows > 0) {
        echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "<p style=\'font-size: 2rem; color: #555;\'>ไม่สามารถสมัครสมาชิกได้",
                            html: "<p style=\'font-size: 1.5rem; color: #555;\'>อีเมลนี้ถูกใช้ไปแล้ว</p>",
                            icon: "error",
                            confirmButtonText: "ตกลง",
                            customClass: {
                            popup: "swal-wide",
                            confirmButton: "swal-button"
                        }
                        }).then(function() {
                            window.location.href = "sign_upform.php";
                        });
                    };
                </script>';
    } elseif ($password <> $passwordcheck) {
        echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "<p style=\'font-size: 2rem; color: #555;\'>ไม่สามารถสมัครสมาชิกได้",
                            html: "<p style=\'font-size: 1.5rem; color: #555;\'>กรุณากรอกรหัสผ่านให้ตรงกัน</p>",
                            icon: "error",
                            confirmButtonText: "ตกลง",
                            customClass: {
                            popup: "swal-wide",
                            confirmButton: "swal-button"
                        }
                        }).then(function() {
                            window.location.href = "sign_upform.php";
                        });
                    };
                </script>';
    } else {
        $insert = "INSERT INTO userdata (email, password, first_name, last_name, role) VALUES ('$email', '$password', '$firstname', '$lastname', 'user')";
        if ($conn->query($insert) == TRUE) {

            $sql = "SELECT * FROM `userdata` WHERE `email`='$email' AND `password`='$password'";
            $check = $conn->query($sql);
            if ($check->num_rows > 0) {
                // session_start();
                $row = $check->fetch_assoc();
                $_SESSION['email'] = $row['email'];
                echo '<script>
                    window.onload = function() {
                        Swal.fire({
                            title: "<p style=\'font-size: 2rem; color: #555;\'>สมัครสมาชิกสำเร็จ",
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
            } else {
                echo "บัคฮะแก้ที" . $conn->error;
            }
        }
    }
}
