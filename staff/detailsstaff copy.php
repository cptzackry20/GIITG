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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['staffImage'])) {
        $uploadDir = 'path_to_upload_folder/'; // Specify the folder to upload the images
        $uploadFile = $uploadDir . basename($_FILES['staffImage']['name']);

        if (move_uploaded_file($_FILES['staffImage']['tmp_name'], $uploadFile)) {
            $updateQuery = "UPDATE staff SET dp = ? WHERE id = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("si", $uploadFile, $staffID);
            if ($stmt->execute()) {
                header("Location: detailsstaff.php");
                exit();
            } else {
                echo "Error updating the database.";
            }
        } else {
            echo "Error uploading the file.";
        }
    }
    if (isset($_POST['staffCode'])) {
        $staffCode = $_POST['staffCode'];
        $updateQuery = "UPDATE staff SET code = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $staffCode, $staffID);
        if ($stmt->execute()) {
            header("Location: detailsstaff.php");
            exit();
        } else {
            echo "Error updating the database.";
        }
    }
    if (isset($_POST['staffName'])) {
        $staffName = $_POST['staffName'];
        $updateQuery = "UPDATE staff SET name = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $staffName, $staffID);
        if ($stmt->execute()) {
            header("Location: detailsstaff.php");
            exit();
        } else {
            echo "Error updating the database.";
        }
    }
    if (isset($_POST['staffEmail'])) {
        $staffEmail = $_POST['staffEmail'];
        $updateQuery = "UPDATE staff SET email = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $staffEmail, $staffID);
        if ($stmt->execute()) {
            header("Location: detailsstaff.php");
            exit();
        } else {
            echo "Error updating the database.";
        }
    }
    if (isset($_POST['staffPosition'])) {
        $staffPosition = $_POST['staffPosition'];
        $updateQuery = "UPDATE staff SET position = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("si", $staffPosition, $staffID);
        if ($stmt->execute()) {
            header("Location: detailsstaff.php");
            exit();
        } else {
            echo "Error updating the database.";
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
                    <form action="detailsstaff.php" method="post" enctype="multipart/form-data">
                        <label for="staffCode">Code</label>
                        <input type="text" id="staffCode" name="staffCode" value="<?php echo $staffCode; ?>">
                        <label for="staffName">Full Name</label>
                        <input type="text" id="staffName" name="staffName" value="<?php echo $staffName; ?>">
                        <label for="staffEmail">Email</label>
                        <input type="email" id="staffEmail" name="staffEmail" value="<?php echo $staffEmail; ?>">
                        <label for="staffPosition">Position</label>
                        <input type="text" id="staffPosition" name="staffPosition" value="<?php echo $staffPosition; ?>">
                        <label for="staffImage">Display Picture</label>
                        <img src="<?php echo $staffImage; ?>" alt="Profile Picture" style="max-width: 100px; max-height: 100px;">
                        <input type="file" id="staffImage" name="staffImage">
            
                <button type="submit" name="submit">Save</button>
                <button type="button" id="discard-button">Discard</button>
            </form>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        var hamburger = document.querySelector(".hamburger");
        hamburger.addEventListener("click", function(){
            document.querySelector("body").classList.toggle("active");
        })
    </script>
</body>
</html>
