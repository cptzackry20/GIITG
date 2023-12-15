<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

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

    // Now, let's fetch the courses enrolled by the staff member
    $coursesQuery = "SELECT course.* FROM coursetaken
                     INNER JOIN course ON coursetaken.course_id = course.id
                     WHERE coursetaken.staff_id = ?";

    $coursesStmt = $conn->prepare($coursesQuery);
    $coursesStmt->bind_param("i", $staffID);
    $coursesStmt->execute();
    $coursesResult = $coursesStmt->get_result();
} else {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="coursetaken.css"> <!-- Include your new CSS file -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="coursetaken.css">
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
                <h1>Course Details</h1>
                <div class="course-details">
    <img src="../<?= $courseImage ?>" alt="Course Image">
    <h2><?= $courseName ?></h2>
    <br>
    <p><?= html_entity_decode($courseDescription) ?></p>
    <p>Duration: <?= $courseDuration ?></p>
    <br>
    <!-- Finish Course Button -->
    <?php
if ($courseFinish == 1) {
    // Course is finished, show the "Go to Quiz" button
    echo '<button id="goToQuizButton" onclick="goToQuiz()" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Go to Quiz</button>';
    echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    echo '<button id="goToFeedback" onclick="goToFeedback()" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Give Feedback</button>';
} else {
    // Course is not finished, display "Finish Course" button
    echo '<button class="finish-button" onclick="finishCourse()" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">Finish Course</button>';
}

?>
<br>
<!-- Add content specific to the course -->
<br>
<h2>Lesson List</h2>
<ul class="lessons-list">
    <?php
    while ($lesson = $lessonsResult->fetch_assoc()) {
        echo '<li>';
        echo '<a href="lessontaken.php?lesson_id=' . $lesson['id'] . '">';
        echo '<h3>' . html_entity_decode($lesson['name']) . '</h3>';
        echo '</a>';
        echo '</li>';
    }
    ?>
</ul>
</div>
</div>
</div>
</div>
<script>
var hamburger = document.querySelector(".hamburger");
hamburger.addEventListener("click", function(){
    document.querySelector("body").classList.toggle("active");
});

// JavaScript function to finish the course
function finishCourse() {
    // Update the database to mark the course as finished
    // You can use an AJAX request or a PHP script to update the 'finish' status
    // Example AJAX request:
    var courseId = <?= $courseId ?>;
    var staffId = <?= $staffID ?>;
    var finishStatus = 1; // Set to 1 to mark it as finished
    var url = 'updateFinishStatus.php'; // Replace with the actual URL
    var params = 'course_id=' + courseId + '&staff_id=' + staffId + '&finish=' + finishStatus;
    var xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Course finished successfully, update the button and display the "Go to Quiz" button
            document.getElementById('goToQuizButton').style.display = 'block';
            document.querySelector('.finish-button').style.display = 'none'; // Hide the "Finish Course" button
        } else {
            // Handle errors
            alert('Failed to mark the course as finished.');
        }
    };

    xhr.send(params);
}

// JavaScript function to go to the quiz
function goToQuiz() {
    // Redirect to the quiz page
    window.location.href = 'quiztaken.php?course_id=<?= $courseId ?>';
}

function goToFeedback() {
    // Correct the link formation for the feedback page
    window.location.href = 'feedbackcourse.php?course_id=<?= $courseId ?>';
}
</script>
</body>
</html>

