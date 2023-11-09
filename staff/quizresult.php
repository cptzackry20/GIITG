<?php
include '../includes/config.php'; // Include the configuration file
session_start();
$staffID = $_SESSION['user']['id']; // Assuming you store the user's ID in the session

// Check if the staff is logged in and has access to this course
if (!isset($_SESSION['user'])) {
    // Redirect to the login page or display an error message
    header("Location: quizresult.php?course_id=" . $courseId);
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
    $staffCode = $row['code'];
    $staffName = $row['name'];
    $staffEmail = $row['email'];
    $staffPosition = $row['position'];
    $staffImage = $row['dp'];
} else {
    echo "Staff details not found.";
    exit();
}

// Query to fetch course details based on the course ID
$courseQuery = "SELECT * FROM course WHERE id = ?";
$courseStmt = $conn->prepare($courseQuery);
$courseStmt->bind_param("i", $courseId);
$courseStmt->execute();
$courseResult = $courseStmt->get_result();

if ($courseResult && $courseResult->num_rows > 0) {
    $course = $courseResult->fetch_assoc();
    $courseName = $course['name'];
} else {
    echo "Course details not found.";
    exit();
}

$questionsQuery = "SELECT * FROM question WHERE quiz_id = (SELECT id FROM quiz WHERE course_id = ?)";
$questionsStmt = $conn->prepare($questionsQuery);
$questionsStmt->bind_param("i", $courseId);
$questionsStmt->execute();
$questionsResult = $questionsStmt->get_result();
$totalQuestions = $questionsResult->num_rows;

$quizResult = isset($_GET['result']) ? (int)$_GET['result'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="quiz.css"> <!-- Include your existing CSS file -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
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
                <h1>Quiz Result for <?= $courseName ?></h1>
                <div class="quiz-result">
                    <p>Your quiz result for the <?= $courseName ?> course:</p>
                    <p>Total Questions: <?= $totalQuestions ?></p>
                    <p>Correct Answers: <?= $quizResult ?></p>
                    <p>Incorrect Answers: <?= $totalQuestions - $quizResult ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
