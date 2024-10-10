<?php
include "nav_bar.php";

// ตรวจสอบการส่งข้อมูลฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $issue_title = $_POST['issue_title'];
    $issue_description = $_POST['issue_description'];
    // $issue_attachment = $_FILES['issue_attachment']['name'];

    // อัปโหลดไฟล์ถ้ามี
    // if ($issue_attachment) {
    //     $target_dir = "uploads/";
    //     $target_file = $target_dir . basename($issue_attachment);
    //     move_uploaded_file($_FILES['issue_attachment']['tmp_name'], $target_file);
    // }

    // เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO reports (issue_title, issue_description, issue_attachment) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $issue_title, $issue_description, $issue_attachment);

    if ($stmt->execute()) {
        echo "<p class='text-green-500'></p>";
    } else {
        echo "<p class='text-red-500'>เกิดข้อผิดพลาด: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

// ดึงข้อมูลจากตาราง reports
$sql = "SELECT * FROM reports ORDER BY issue_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send and View Reports</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet"> -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            /* background-image: url('pik/logo.png');
            background-repeat: no-repeat;
            background-attachment: fixed; */
            /* background-size: cover; */
        }

        .content_container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
            width: 80%;
            position: relative;
        }

        .input_align {
            width: 95%;
            margin: 15px;
            padding: 5px;
            text-align: left;
            display: block;
        }
        .report_button{
            padding: 10px !important;
            border:none;
            border-radius: 5px;
            background-color: #f1f1f1;
        }
        .report_button:hover{
            opacity: 0.6;
        }
    </style>
    <script>
        function confirmSubmission() {
            return confirm("แน่ใจหรือไม่ว่าต้องการส่งรายงานนี้?");
        }
    </script>
</head>

<body>
    <br>
    <div class="content_container">
        <h1 class="">ส่งรายงานปัญหา</h1><br>
        <form id="reportForm" action="" method="post" enctype="multipart/form-data" onsubmit="return confirmSubmission()">
            <div class="mb-4">
                <label for="issue_title" class="block text-gray-700">หัวข้อปัญหา</label>
                <input type="text" id="issue_title" name="issue_title" class="input_align" required>
            </div>
            <div class="mb-4">
                <label for="issue_description" class="block text-gray-700">รายละเอียดปัญหา</label>
                <textarea id="issue_description" name="issue_description" rows="4" class="input_align" required></textarea>
            </div>

            <button type="submit" class="report_button">ส่งรายงาน</button>
        </form>
    </div>



</body>

</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();
?>