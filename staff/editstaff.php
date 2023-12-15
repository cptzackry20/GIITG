<?php
require_once '../includes/config.php'; // Include the configuration file


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
<link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Edit Profile Details</title>
    <link rel="stylesheet" href="../style/showstaff.css">
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php include '../includes/navbar2.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
            <h1>Edit Profile</h1>
                <div class="edit-profile-form">
                    <form action="saveupdateprofile.php" method="post" enctype="multipart/form-data">
                    <h4 for="staffImage">Display Picture</h4>
<div style="display: flex; justify-content: center; align-items: center; margin-bottom: 15px;">
    <img src="<?php echo $staffImage; ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px; border-radius: 50%;">
</div>
                      <input type="file" id="staffImage" name="staffImage">

                        <label for="staffName">Full Name</label>
                        <input type="text" id="staffName" name="staffName" value="<?php echo $staffName; ?>">
                        
                        <label for="staffEmail">Email</label>
                        <input type="email" id="staffEmail" name="staffEmail" value="<?php echo $staffEmail; ?>">
                        <div class="confirm">
    <button type="submit" name="submit">Save</button>
    <button type="submit" id="cancel-button" onclick="window.location.href='showstaff.php';">Cancel</button>
</div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php include('../includes/footer.php'); ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.js"
        integrity="sha512-Gx/C4x1qubng2MWpJIxTPuWch9O88dhFFfpIl3WlqH0jPHtCiNdYsmJBFX0q5gIzFHmwkPzzYTlZC/Q7zgbwCw=="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
