<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // ใช้การตรวจสอบก่อนเพื่อหลีกเลี่ยงการเรียกใช้หลายครั้ง
    }

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
                $order = "ตรวจสอบออเดอร์";
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
        $order = "";

    }
    ?>

    <div class="bg-white">
        <header class="relative">
            <nav aria-label="Top">
                <div class="bg-gray-900">
                    <div class="mx-auto flex h-10 max-w-7xl items-center justify-end px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center space-x-6">
                            <a href="user_edit.php"
                                class="text-sm font-medium text-white hover:text-gray-100"><?php echo "$you $firstname"; ?></a>
                            <button class="text-red-300 hover:text-white" onclick="confirmLogout()">
                                <?php echo $logout; ?>
                            </button>

                            <a href="login.php"
                                class="text-sm font-medium text-white hover:text-gray-100"><?php echo $login; ?></a>
                            <a href="sign_upform.php"
                                class="text-sm font-medium text-white hover:text-gray-100"><?php echo $signup; ?></a>
                        </div>
                    </div>
                </div>

                <div class="bg-white">
                    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div class="border-b border-gray-200">
                            <div class="flex h-16 items-center justify-between">
                                <div class=" items-center">

                                    <a href="index.php"><img class="h-12 w-auto" src="pik/logo.png" alt="Logo"></a>
                                </div>

                                <div class=" items-center justifly-center space-x-8">
                                    <a href="index.php"
                                        class="text-sm font-medium text-gray-700 hover:text-gray-800">สินค้าทั้งหมด</a>

                                    <a href="contactus.php"
                                        class="text-sm font-medium text-gray-700 hover:text-gray-800">ช่องทางติดต่อเพิ่มเติม</a>
                                    <a href="cart.php"
                                        class="text-sm font-medium text-gray-700 hover:text-gray-800"><?php echo $cart; ?></a>
                                    <a href="order.php"
                                        class="text-sm font-medium text-gray-700 hover:text-gray-800"><?php echo $order; ?></a>
                                </div>

                                <div class=" items-center justifly-end space-x-4">

                                    <a href="#" class="group -m-2 flex items-center p-2">
                                        <svg class="h-6 w-6 flex-shrink-0 text-gray-400 group-hover:text-gray-500"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                        <span
                                            class="ml-2 text-sm font-medium text-gray-700 group-hover:text-gray-800">0</span>
                                        <span class="sr-only">items in cart, view bag</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    </div>
</body>



<script>
    // ฟังก์ชันสำหรับการยืนยันการออกจากระบบ
    function confirmLogout() {
        // เรียกใช้ SweetAlert สำหรับการยืนยัน
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณต้องการออกจากระบบใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ออกจากระบบ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้าผู้ใช้กดยืนยัน ให้นำไปยังหน้า logout.php
                window.location.href = "./logout.php";
            }
        })
    }
</script>


