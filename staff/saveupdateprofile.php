<?php
require_once '../includes/config.php'; // Include the configuration file
session_start();

$staffID = $_SESSION['user']['id'];

$query = "SELECT id, code, name, email, password, position, dp FROM staff WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $staffName = $row['name'];
    $staffEmail = $row['email'];
    $staffImage = $row['dp'];
    $staffPosition = $row['position'];
    $staffCode = $row['code'];
    $currentPassword = $row['password'];
} else {
    echo "Staff details not found.";
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission here

    // Check if the current password matches the one in the database
    $currentPasswordInput = $_POST['staffCurrentPassword'];
    $newPasswordInput = $_POST['staffNewPassword'];

    if (password_verify($currentPasswordInput, $currentPassword)) {
        // Current password matches, proceed with updating the new password
        $newPasswordHash = password_hash($newPasswordInput, PASSWORD_DEFAULT);

        $updateQuery = "UPDATE staff SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $newPasswordHash, $staffID);

        if ($stmt->execute()) {
            // Password updated successfully
            // You can redirect or display a success message here
        } else {
            // Handle the database update error
            echo "Error updating the password.";
        }
    } else {
        // Handle the case where the current password does not match
        echo "Current password is incorrect.";
    }
}
?>
