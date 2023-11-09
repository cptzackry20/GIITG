<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

// Start the session at the beginning
session_start();

// Fetch staff details from the database based on the user's session information
$staffID = $_SESSION['user']['id']; // Assuming you store the user's ID in the session

// Query to fetch staff details based on the user's ID
$query = "SELECT * FROM staff WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Replace the following placeholders with actual data from the database
    $staffCode = $row['code'];
    $staffName = $row['name'];
    $staffEmail = $row['email'];
    $staffPosition = $row['position'];
    $staffImage = $row['dp'];
} else {
    // Handle the case where the staff member's details are not found
    // You can redirect to an error page or display an error message
    echo "Staff details not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="mycourse.css"> <!-- You may need a separate CSS file for staffchangepassword.php -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title> <!-- Update the title as needed -->
    <link rel="shortcut icon" href="https://i.ibb.co/swfD2Yt/giitglogo-01-01.png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="staffchangepassword.css">
    
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
            <div class="container">
                <h1>Change Password</h1>
                <!-- Create the form for changing the password -->
                <form action="savechangepassword.php" method="post">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>

                    <label for="newPassword">New Password</label>
                    <input type="password" id="newPassword" name="newPassword" required>

                    <label for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>

                    <button type="submit" name="submit">Change Password</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        var hamburger = document.querySelector(".hamburger");
        hamburger.addEventListener("click", function(){
            document.querySelector("body").classList.toggle("active");
        });
    </script>
</body>
</html>
