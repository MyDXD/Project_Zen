<?php
include "nav_bar.php";
?>

<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<title>สมัครสมาชิก</title>

<body>

    <div class="container mx-auto bg-white">
        <div
            class="mx-auto bg-gray-100 rounded-lg shadow-md mt-2 max-w-2xl px-4 py-16 sm:px-6 sm:py-16 lg:max-w-7xl lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-gray-900">สมัครสมาชิก</h2>
            </div>

            <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                <form class="space-y-6" action="sign_up.php" method="post">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-900">ชื่อจริง</label>
                        <div class="mt-2">
                            <input id="first_name" name="first_name" type="text" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-900">นามสกุล</label>
                        <div class="mt-2">
                            <input id="last_name" name="last_name" type="text" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-900">อีเมล</label>
                        <div class="mt-2">
                            <input id="email" name="email" type="email" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-900">เบอร์โทรศัพท์</label>
                        <div class="mt-2">
                            <input id="phone_number" name="phone_number" type="text" required maxlength="10"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-900">รหัสผ่าน</label>
                        <div class="mt-2">
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="passwordcheck"
                            class="block text-sm font-medium text-gray-900">ยืนยันรหัสผ่าน</label>
                        <div class="mt-2">
                            <input id="passwordcheck" name="passwordcheck" type="password" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
                            ที่อยู่สำหรับจัดส่ง</label>
                        <div class="mt-2">
                            <textarea id="address" name="address" autocomplete="address" required
                                class="block p-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                rows="4"></textarea>
                        </div>
                    </div>

                    <div>
                        <button type="submit" name="sign_up"
                            class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">สมัครสมาชิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</body>