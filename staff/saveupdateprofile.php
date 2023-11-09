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

    if (isset($_FILES['staffImage']) && $_FILES['staffImage']['error'] === UPLOAD_ERR_OK) {
        // A new file is selected for upload
        $uploadDir = 'path_to_upload_folder/'; // Specify the folder to upload the images
        $uploadFile = $uploadDir . basename($_FILES['staffImage']['name']);

        if (move_uploaded_file($_FILES['staffImage']['tmp_name'], $uploadFile)) {
            // Update the 'dp' field in the database with the new image path
            $updateQuery = "UPDATE staff SET dp = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $uploadFile, $staffID);
            if ($stmt->execute()) {
                // Image uploaded and database updated successfully
            } else {
                // Handle the database update error
                echo "Error updating the database.";
            }
        } else {
            // Handle the file upload error
            echo "Error uploading the file.";
        }
    } elseif (!isset($_FILES['staffImage']) || $_FILES['staffImage']['error'] === UPLOAD_ERR_NO_FILE) {
        // No new file was selected, do nothing
    } else {
        echo "File upload error: " . $_FILES['staffImage']['error'];
    }

    // Update staff position
    $newPosition = $_POST['staffPosition'];

    if ($newPosition !== $staffPosition) {
        $updatePositionQuery = "UPDATE staff SET position = ? WHERE id = ?";
        $stmt = $conn->prepare($updatePositionQuery);
        $stmt->bind_param("si", $newPosition, $staffID);
        if ($stmt->execute()) {
            // Position updated successfully
        } else {
            // Handle the database update error
            echo "Error updating the position.";
        }
    }

    // Handle password update only if a new password is provided
    if (isset($_POST['staffNewPassword']) && !empty($_POST['staffNewPassword'])) {
        $staffNewPassword = password_hash($_POST['staffNewPassword'], PASSWORD_DEFAULT);

        $updateQuery = "UPDATE staff SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $staffNewPassword, $staffID);

        if ($stmt->execute()) {
            // Password updated successfully
        } else {
            // Handle the database update error
            echo "Error updating the password.";
        }
    }

    // Redirect to the profile page with updated data
    header("Location: detailsstaff.php");
    exit();
}
?>
