<?php
// Include the database configuration file
include '../includes/config.php'; // Adjust the path as needed

// Start the session
session_start();

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    // Retrieve the current password, new password, and confirm password
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Fetch staff details from the session
    $staffID = $_SESSION['user']['id'];

    // Query to fetch the current password from the database
    $query = "SELECT password FROM staff WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $staffID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the current password
        if (password_verify($currentPassword, $storedPassword)) {
            // Current password is correct

            // Verify if the new password and confirm password match
            if ($newPassword === $confirmPassword) {
                // Hash the new password before saving it
                $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                // Update the password in the database
                $updateQuery = "UPDATE staff SET password = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("si", $hashedNewPassword, $staffID);

                if ($updateStmt->execute()) {
                    // Password updated successfully
                    echo "Password updated successfully.";
                } else {
                    echo "Error updating password: " . $conn->error;
                }
            } else {
                echo "New password and confirm password do not match.";
            }
        } else {
            echo "Current password is incorrect.";
        }
    } else {
        echo "User not found.";
    }
} else {
    // If the form was not submitted, redirect or display an error message
    echo "Form not submitted.";
}
?>
