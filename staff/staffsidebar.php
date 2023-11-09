<?php
require_once '../includes/config.php'; // Include the configuration file



$staffID = $_SESSION['user']['id'];

$query = "SELECT * FROM staff WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staffID);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['staffPosition'] = $row['position']; // Set staffPosition in the session
}
?>

<div class="sidebar">
    <div class="profile">
        <img src="<?php echo $staffImage; ?>" alt="Profile Picture">
        <h3><?php echo $staffName; ?></h3>
        <p><?php echo $_SESSION['staffPosition']; ?></p> <!-- Access staffPosition from the session -->
    </div>
    <ul>
        <li><a href="../index.php">
            <span class="icon"><i class="fas fa-home"></i></span>
            <span class="item">Home</span>
        </a></li>
        <li><a href="detailsstaff.php">
            <span class="icon"><i class="fas fa-desktop"></i></span>
            <span class="item">Edit Profile</span>
        </a></li>
        <li><a href="staffchangepassword.php">
            <span class="icon"><i class="fas fa-desktop"></i></span>
            <span class="item">Edit Password</span>
        </a></li>
        <li><a href="mycourse.php">
            <span class="icon"><i class="fas fa-database"></i></span>
            <span class="item">My Course</span>
        </a></li>
        <li><a href="../feedback.php">
            <span class="icon"><i class="fas fa-comment-alt"></i></span>
            <span class="item">Feedback</span>
        </a></li>
        <li><a href="../logout.php">
            <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
            <span class="item">Logout</span>
        </a></li>
    </ul>
</div>
