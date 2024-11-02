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

    .content_right_button {
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


    <!-- <div class="content_left">
        <img src="pik/logo.png">
    </div>
    <div class="content_right">
        <h2>เข้าสู่ระบบ</h2>
        <form action="login.php" method="post">
            <input type="email" placeholder="อีเมล" name="email" required>
            <input type="password" placeholder="รหัสผ่าน" name="password" required>
            <input type="submit" value="เข้าสู่ระบบ" name="login" class="content_right_button">
        </form>
    </div> -->
    
    <div class="container mx-auto bg-white">
        <div
            class="mx-auto bg-gray-100 rounded-lg shadow-md mt-2 max-w-2xl px-4 py-16 sm:px-6 sm:py-16 lg:max-w-7xl lg:px-8">
                    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                        <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your
                            account</h2>
                    </div>

                    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                        <form class="space-y-6" action="login.php" method="POST">
                            <div>
                                <label for="email" class="block text-sm/6 font-medium text-gray-900">Email
                                    address</label>
                                <div class="mt-2">
                                    <input id="email" name="email" type="email" autocomplete="email" required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between">
                                    <label for="password"
                                        class="block text-sm/6 font-medium text-gray-900">Password</label>
                                  
                                </div>
                                <div class="mt-2">
                                    <input id="password" name="password" type="password" autocomplete="current-password"
                                        required
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6">
                                </div>
                            </div>

                            <div>
                                <button type="submit" name="login"
                                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign
                                    in</button>
                            </div>
                        </form>

                        <p class="mt-10 text-center text-sm/6 text-gray-500">
                            ยังไม่เป็นสมาชิก ?
                            <a href="./sign_upform.php" class="font-semibold text-indigo-600 hover:text-indigo-500">สมัครสมาชิก</a>
                        </p>
                    </div>
                </div>

            </div>



        </div>
    </div>


</body>