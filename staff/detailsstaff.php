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
    $currentPassword = $row['password']; // Retrieve the current password
} else {
    echo "Staff details not found.";
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission here
    // For example, handle the uploaded image and update the database
    if (isset($_FILES['staffImage'])) {
        $uploadDir = 'path_to_upload_folder/'; // Specify the folder to upload the images
        $uploadFile = $uploadDir . basename($_FILES['staffImage']['name']);

        if (move_uploaded_file($_FILES['staffImage']['tmp_name'], $uploadFile)) {
            // Update the 'dp' field in the database with the new image path
            $updateQuery = "UPDATE staff SET dp = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $uploadFile, $staffID);
            if ($stmt->execute()) {
                // Image uploaded and database updated successfully
                // Redirect to the profile page or display a success message
                header("Location: detailsstaff.php");
                exit();
            } else {
                // Handle the database update error
                echo "Error updating the database.";
            }
        } else {
            // Handle the file upload error
            echo "Error uploading the file.";
        }
    }

    // Handle other form fields for updating the profile here, including password
    if (isset($_POST['staffNewPassword'])) {
        $staffNewPassword = password_hash($_POST['staffNewPassword'], PASSWORD_DEFAULT);

        $updateQuery = "UPDATE staff SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $staffNewPassword, $staffID);
        if ($stmt->execute()) {
            // Password updated successfully
            // You can redirect or display a success message here
        } else {
            // Handle the database update error
            echo "Error updating the password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="detailsstaff.css">

    <style>
        /* Style for password input when it's hidden */
        .hidden-password {
            display: none; /* Hide the password text by default */
        }
    </style>
</head>
<body>
<div class="wrapper">
    <?php include 'staffsidebar.php'; ?>
    <div class="section">
            <div class="top_navbar">
                <div class="hamburger">
                    <a href="#"><i class="fas fa-bars"></i></a>
                </div>
            </div>
    <div class="form-container">
        
        
        <div class="edit-profile-form">
            <h1>Edit Profile</h1>
            <form action="saveupdateprofile.php" method="post" enctype="multipart/form-data">
                <label for="staffName">Full Name</label>
                <input type="text" id="staffName" name="staffName" value="<?php echo $staffName; ?>">
                <label for="staffEmail">Email</label>
                <input type="email" id="staffEmail" name="staffEmail" value="<?php echo $staffEmail; ?>">
                
                <label for="staffCurrentPassword">Current Password</label>
                <span class="current-password" id="staffCurrentPassword"><?php echo $currentPassword; ?></span>
                <div class="eye-icon" id="eye-icon-current">
                <i class="fas fa-eye"></i>
            </div>

            <label for="staffNewPassword">New Password</label>  

            <input type="password" id="staffNewPassword" name="staffNewPassword">
            <div class="eye-icon" id="eye-icon-new">
                
            <i class="fas fa-eye"></i>
            </div>
                <label for="staffCode">Code</label>
                <input type="text" id="staffCode" name="staffCode" value="<?php echo $staffCode; ?>">
                
                <label for="staffPosition">Position</label>
                <input type="text" id="staffPosition" name="staffPosition" value="<?php echo $staffPosition; ?>">
                
                <label for="staffImage">Display Picture</label>
                <img src="<?php echo $staffImage; ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">
                <input type="file" id="staffImage" name="staffImage">



                <button type="submit" name="submit">Save</button>
                <button type="button" id="discard-button">Discard</button>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    var eyeIconCurrent = document.getElementById("eye-icon-current");
var eyeIconNew = document.getElementById("eye-icon-new");
var currentPassword = document.getElementById("staffCurrentPassword");
var newPasswordInput = document.getElementById("staffNewPassword");

eyeIconCurrent.addEventListener("click", function () {
    if (currentPassword.style.display === "none") {
        currentPassword.style.display = "inline";
    } else {
        currentPassword.style.display = "none";
    }
});

eyeIconNew.addEventListener("click", function () {
    if (newPasswordInput.type === "password") {
        newPasswordInput.type = "text";
    } else {
        newPasswordInput.type = "password";
    }
});


    var discardButton = document.getElementById("discard-button");
    discardButton.addEventListener("click", function() {
        // Reload the page to discard changes
        location.reload();
    });

    var hamburger = document.querySelector(".hamburger");
    hamburger.addEventListener("click", function(){
        document.querySelector("body").classList.toggle("active");
    });
</script>
</body>
</html>