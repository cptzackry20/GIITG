<?php
require_once '../includes/config.php'; // Include the configuration file
session_start();

$staffID = $_SESSION['user']['id'];

$query = "SELECT id, code, name, email, position, dp FROM staff WHERE id = ?";
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
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff's Profile</title>
    <link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="detailsstaff.css">
</head>
<style>
    /* Style the upload button */
    .upload-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
        text-align: center;
    }

    /* Style the upload button on hover */
    .upload-button:hover {
        background-color: #9bb5d1;
    }
</style>

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
                <label for="staffCode">Code</label>
                <input type="text" id="staffCode" name="staffCode" value="<?php echo $staffCode; ?>">
                <label for="staffPosition">Position</label>
                <input type="text" id="staffPosition" name="staffPosition" value="<?php echo $staffPosition; ?>">
                
                <img src="<?php echo $staffImage; ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">
                <input type="file" id="staffImage" name="staffImage">
                <!-- Add a new input field for image URL -->
                <label for="staffImageUrl">Or image URL (or leave it empty if uploading a file)</label>
                <input type="text" id="staffImageUrl" name="staffImageUrl">
                <br>
                <button type="submit" name="submit">Save</button>
                <button type="button" id="discard-button">Discard</button>
            </form>
        </div>
    </div>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
