<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <title>แก้ไขข้อมูล</title>
</head>
<?php include 'nav_bar.php';
include("dbconnect.php");
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = mysqli_query($conn, "SELECT * FROM `userdata` WHERE `email`='$email'");

    if (!$query) {
        echo "Error: " . mysqli_error($conn);
    } else {
        if ($row = mysqli_fetch_assoc($query)) {
            $firstname = $row['first_name'];
            $lastname = $row['last_name'];
            $email = $row['email'];
            $phone_number = $row['phone_number'];
            $password = $row['password'];
            $address = $row['address'];
            $user_id = $row['user_id'];
        }
    }
}
?>

<?php
if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastname = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $update = "UPDATE `userdata` SET `email`='$email', `password`='$hashed_password', `first_name`='$firstname', `last_name`='$lastname', `address`='$address', `phone_number`='$phone_number' WHERE user_id=$user_id";
    if ($conn->query($update) == TRUE) {
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
    } else {
        echo "แตก";
    }
}
?>


<body>
    <div class="bg-white">
        <div
            class="mx-auto max-w-2xl bg-gray-100 mt-2 rounded-lg shadow-md px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
            <div class="flex min-h-full flex-col justify-center">
                <h2 class="text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">แก้ไขโปรไฟล์
                </h2>
                <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                    <form action="" method="post"  class="editform">
                        <div>
                            <label for="first_name" class="block text-sm font-medium leading-6 text-gray-900">
                                ชื่อจริง</label>
                            <div class="mt-2">
                                <input id="first_name" name="first_name" type="text" autocomplete="first_name" required
                                    class="block p-2  w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    value="<?php echo $firstname; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium leading-6 text-gray-900">
                                นามสกุล</label>
                            <div class="mt-2">
                                <input id="last_name" name="last_name" type="text" autocomplete="last_name" required
                                    class="block p-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    value="<?php echo $lastname; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
                                อีเมล</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email" required
                                    class="block p-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    value="<?php echo $email; ?>">
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password"
                                    class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                            </div>
                            <div class="mt-2">
                                <input id="password" name="password" type="password" autocomplete="current-password"
                                    required
                                    class="block p-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    value="<?php echo $password; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="phone_number" class="block text-sm font-medium leading-6 text-gray-900">
                                เบอร์โทรศัพท์</label>
                            <div class="mt-2">
                                <input id="phone_number" name="phone_number" type="text" autocomplete="phone_number"
                                    required
                                    class="block p-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    value="<?php echo $phone_number; ?>">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">
                                ที่อยู่สำหรับจัดส่ง</label>
                            <div class="mt-2">
                                <textarea id="address" name="address" autocomplete="address" required
                                    class="block p-2 w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    rows="4"><?php echo $address; ?></textarea>
                            </div>
                        </div>


                        <div>
                            <button type="submit" name="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>