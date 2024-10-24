<?php
include "nav_bar.php";
?>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>เข้าสู่ระบบ</title>
</head>
<style>
    h1 {
        display: block;
        margin-left: 20%;
        padding-top: 20px;
    }

    .content {
        margin-left: 15%;
        margin-right: 15%;
        position: relative;
        margin-top: 20px;
    }

    .content_left {
        display: inline-block;
        margin-left: 15px;
        margin-right: 15px;
        position: absolute;
        top: 25%;
        left: 10%;
        z-index: -1;
        width: 40%;
    }

    .content_right {
        margin-left: 20%;
        margin-right: 15px;
        position: absolute;
        top: 25%;
        right: 15%;
        width: 30%;
        display: inline-block;
    }

    .content_right input {
        display: block;
        width: 90%;
        padding: 10px;
        margin-top: 30px;
        border: 1px solid #000;
        border-radius: 1px;
    }

    .content_right_button{
        width: 95% !important;
    }
    h2 {
        margin-left: 10px;
        color: #000;
    }

    body {
        background-color: #ffffff;
    }
</style>
<body>


    <div class="content_left">
        <img src="pik/logo.png">
    </div>
    <div class="content_right">
        <h2>เข้าสู่ระบบ</h2>
        <form action="login.php" method="post">
            <input type="email" placeholder="อีเมล" name="email" required>
            <input type="password" placeholder="รหัสผ่าน" name="password" required>
            <input type="submit" value="เข้าสู่ระบบ" name="login" class="content_right_button">
        </form>
    </div>

</body>