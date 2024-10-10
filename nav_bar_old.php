<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            padding: 0;
            margin: 0;
        }

        body {
            margin: 0 !important;
            font-family: Arial, sans-serif !important;
        }

        a {
            color: #333 !important;
            text-decoration: none;
        }

        .header {
            background-color: #F7E7C4;
            color: #333;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 0 3%;
        }

        .logo img {
            width: 100%;
            height: 100%;
            max-width: 70px;
            position: relative;
            left: -10%;
            border-radius: 1rem;
            margin: 5px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-bar-form {
            display: flex;
            align-items: center;
            gap: 0px;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            border: 1px solid #D0BFA4;
            outline: none;
        }

        .search-button {
            width: 20%;
            padding: 10px;
            background: #E2B07F;
            color: white;
            font-size: 13px;
            border: none;
            border-left: none;
            cursor: pointer;
        }

        .menu-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .action-item {
            padding: 5px 5px;
            border-radius: 5px;
            color: #fff;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
            font-size: medium;
        }

        .action-item-dropdown {
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
            font-size: medium;
            position: relative;
            padding: 9px 20px;
            border: none;
            border-radius: 5px;
            background-color: #E2B07F;
            cursor: pointer;
        }

        .action-item-dropdown a {
            color: #fff !important;
        }

        .action-item:hover {
            opacity: 0.8;
        }

        .action-item-dropdown:hover .type-item a {
            display: block;
        }

        .type-item {
            position: absolute;
            background-color: #e7c5a5;
            left: -2px;
        }

        .type-item a {
            display: none;
            padding-left: 10px;
            padding: 10px 40px;
        }

        .type-item a:hover {
            background-color: #E2B07F;
        }
        
    </style>
</head>

<body>

    <?php
    session_start();
    include("dbconnect.php");
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $query = mysqli_query($conn, "SELECT * FROM `userdata` WHERE `email`='$email'");

        if (!$query) {
            echo "Error: " . mysqli_error($conn);
        } else {
            if ($row = mysqli_fetch_assoc($query)) {
                $you = 'คุณ';
                $firstname = $row['first_name'];
                $lastname = $row['last_name'];
                $logout = 'ออกจากระบบ';
                $cart = "ตรวจสอบตระกร้าสินค้า";
                $login = "";
                $signup = "";
            }
        }
    } else {
        $you = "";
        $firstname = "";
        $lastname = "";
        $logout = "";
        $login = "เข้าสู่ระบบ";
        $signup = "สมัครสมาชิก";
        $cart = "";
    }
    ?>
    <div class="header">
        <div class="container">
            <div class="logo">
                <a href="index.php"><img src="pik/logo.png" alt="Logo"></a>
            </div>
            <div class="search-bar">
                <div class="action-item-dropdown">
                    <a href="#">หมวดหมู่สินค้า</a>
                    <div class="type-item">
                        <a href="product_dry_type.php">ตากแห้ง</a>
                        <a href="product_crisp_type.php">อบกรอบ</a>
                        <a href="product_import.php">ขนมนำเข้า</a>
                        <a href="product_coffee_tea.php">ชา กาแฟ</a>
                        <a href="product_cannedfood.php">อาหารกระป๋อง</a>
                    </div>
                </div>
                <form action="" method="post" enctype="multipart/form-data" class="search-bar-form">
                    <input type="submit" value="&#x1F50E;&#xFE0E;" name="search" class="search-button"></input>
                    <input type="text" placeholder="ค้นหาสินค้า..." name="search" class="search-bar"></input>
                </form>
            </div>
            <div class="menu-container">
                <div class="action-item">
                    <a href="index.php">สินค้าทั้งหมด</a>
                </div>
                <div class="action-item">
                    <a href="#"><?php echo $cart; ?></a>
                </div>
                <div class="action-item">
                    <a href="contactus.php">ช่องทางติดต่อเพิ่มเติม</a>
                </div>
                <div class="action-item">
                    <a href="login_form.php"><?php echo $login; ?></a>
                </div>
                <div class="action-item">
                    <a href="sign_upform.php"><?php echo $signup; ?></a>
                </div>
                <div class="action-item">
                    <a href="user_edit.php"><?php echo "$you $firstname"; ?></a>
                </div>
                <div class="action-item">
                    <a href="logout.php"><?php echo $logout; ?></a>
                </div>

            </div>
        </div>
    </div>
    </div>

</body>