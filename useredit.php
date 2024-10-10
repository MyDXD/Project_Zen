<?php
// Database connection
include 'dbconnect.php';

// Handle password update request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the JSON data sent from the frontend
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if the password is received
    if (isset($data['password']) && !empty($data['password'])) {
        session_start(); // Start session if not started already
        $newPassword = $data['password'];

        // Hash the new password for security
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Example: Update password for the logged-in user (assuming you have the user's ID in session)
        $userId = $_SESSION['user_id']; // Get user ID from session

        // SQL query to update the password
        $sql = "UPDATE userdata SET password='$hashedPassword' WHERE user_id='$userId'";

        if (mysqli_query($conn, $sql)) {
            // Success response
            echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
        } else {
            // Error response
            echo json_encode(['success' => false, 'message' => 'Error updating password: ' . mysqli_error($conn)]);
        }
    } else {
        // Invalid request response
        echo json_encode(['success' => false, 'message' => 'No password provided']);
    }
    exit(); // End the script after handling the request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Popup Form</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

    <!-- Trigger Button -->
    <button id="openPasswordForm" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Change Password</button>

    <script>
        // JavaScript to trigger SweetAlert popup
        document.getElementById('openPasswordForm').addEventListener('click', function () {
            Swal.fire({
                title: 'Enter Your New Password',
                html:
                    `<form id="passwordForm">
                        <input type="password" id="newPassword" class="swal2-input" placeholder="New Password" required>
                        <input type="password" id="confirmPassword" class="swal2-input" placeholder="Confirm Password" required>
                    </form>`,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Submit',
                preConfirm: () => {
                    const newPassword = document.getElementById('newPassword').value;
                    const confirmPassword = document.getElementById('confirmPassword').value;

                    if (!newPassword || !confirmPassword) {
                        Swal.showValidationMessage('Please enter both fields.');
                        return false;
                    }

                    if (newPassword !== confirmPassword) {
                        Swal.showValidationMessage('Passwords do not match.');
                        return false;
                    }

                    // If validation is successful, return the password value
                    return { newPassword };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the password via AJAX to PHP script
                    const password = result.value.newPassword;

                    fetch('change_password.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ password: password })
                    }).then(response => response.json())
                      .then(data => {
                          if (data.success) {
                              Swal.fire('Success', data.message, 'success');
                          } else {
                              Swal.fire('Error', data.message, 'error');
                          }
                      }).catch(error => {
                          Swal.fire('Error', 'Failed to change password.', 'error');
                      });
                }
            });
        });
    </script>
</body>
</html>
