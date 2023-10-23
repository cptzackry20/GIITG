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
    <!-- Include necessary meta tags, stylesheets, and scripts -->
    <title>Staff Profile</title>
    <link rel="stylesheet" href="Bootstrap/jsbootstrap.min.css">
    <link rel="stylesheet" href="Bootstrap/jsyour-custom.css">
    <style>
        /* Custom CSS for profile page */
        .content {
            margin-left: 250px; /* Adjust the margin to match your sidebar width */
            padding: 20px;
        }

        .profile-details {
            text-align: center;
        }

        .profile-details img {
            max-width: 200px;
            border-radius: 50%;
        }

        /* Add additional styling as needed */
    </style>
</head>
<body>
    <!-- Include sidebar -->
    <?php include 'staffincludes/sidebar.php'; ?>

    <!-- Staff Profile Content -->
    <div class="content">
        <h1>Staff Profile</h1>
        <div class="profile-details">
            <img src="<?php echo $staffImage; ?>" alt="Staff Image">
            <p><strong>Staff Code:</strong> <?php echo $staffCode; ?></p>
            <p><strong>Staff Name:</strong> <?php echo $staffName; ?></p>
            <p><strong>Email:</strong> <?php echo $staffEmail; ?></p>
            <p><strong>Position:</strong> <?php echo $staffPosition; ?></p>
        </div>
    </div>

    <!-- Include necessary scripts (Bootstrap and your custom scripts) -->
    <script src="Bootstrap/js/jquery.js"></script>
    <script src="Bootstrap/js/bootstrap.min.js"></script>
    <script src="Bootstrap/js/bootstrap.js"></script>
</body>
</html>
