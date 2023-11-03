<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

// Start the session at the beginning
session_start();
$staffID = $_SESSION['user']['id']; // Assuming you store the user's ID in the session

// Check if the staff is logged in and has access to this course
if (!isset($_SESSION['user'])) {
    // Redirect to the login page or display an error message
    header("Location: login.php"); // Replace with the correct login page URL
    exit();
}

// Get the course ID from the URL
$courseId = $_GET['course_id'];

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

// Query to fetch the course finish status from the coursetaken table
$courseFinishQuery = "SELECT finish FROM coursetaken WHERE staff_id = ? AND course_id = ?";
$courseFinishStmt = $conn->prepare($courseFinishQuery);
$courseFinishStmt->bind_param("ii", $staffID, $courseId);
$courseFinishStmt->execute();
$courseFinishResult = $courseFinishStmt->get_result();

if ($courseFinishResult && $courseFinishResult->num_rows > 0) {
    $courseFinishRow = $courseFinishResult->fetch_assoc();
    $courseFinish = $courseFinishRow['finish'];
} else {
    // Handle the case where the course finish status is not found
    echo "Course finish status not found.";
    exit();
}

// Query to fetch the course details based on the course ID
$query = "SELECT * FROM course WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $courseId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $course = $result->fetch_assoc();

    // Assign the course details to variables
    $courseName = $course['name'];
    $courseDescription = $course['desc'];
    $courseDuration = $course['duration'];
    $courseImage = $course['img'];
} else {
    // Handle the case where the course details are not found
    // You can redirect to an error page or display an error message
    echo "Course details not found.";
    exit();
}

// Query to fetch lessons related to the course
$lessonsQuery = "SELECT * FROM lesson WHERE course_id = ?";
$lessonsStmt = $conn->prepare($lessonsQuery);
$lessonsStmt->bind_param("i", $courseId);
$lessonsStmt->execute();
$lessonsResult = $lessonsStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Feedback</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="feedbackcourse.css">
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
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
                <h1>Course Feedback</h1>
                <div class="course-details">
                    <img src="../<?= $courseImage ?>" alt="Course Image">
                    <div class="course-info">
                        <h2><?= $courseName ?></h2>
                        <p><?= html_entity_decode($courseDescription) ?></p>
                        <p>Duration: <?= $courseDuration ?></p>
                    </div>

                    <!-- Add a feedback form here -->
                    <form action="process_feedback.php" method="post">
                        <input type="hidden" name="course_id" value="<?= $courseId ?>">
                        <input type="hidden" name="staff_id" value="<?= $staffID ?>">
                        <label for="feedback">Your Feedback:</label>
                        <textarea name="feedback" id="feedback" rows="5" required></textarea>
                        
                        <!-- Animated Star Rating Input -->
                        <div class="rating-wrapper">
    <h3 class="rating-label">Course Rating</h3>
    <div class="rating-star">
        <input type="radio" id="star5" name="rating" value="5">
        <label for="star5" title="5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4">
        <label for="star4" title="4 stars"></label>
        <input type="radio" id="star3" name="rating" value="3">
        <label for="star3" title="3 stars"></label>
        <input type="radio" id="star2" name="rating" value="2">
        <label for="star2" title="2 stars"></label>
        <input type="radio" id="star1" name="rating" value="1">
        <label for="star1" title="1 star"></label>
    </div>
</div>
                        

                        <button type="submit" class="submit-button">Submit Feedback</button>
                    </form>
                </div>
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
