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

// Query to fetch course details based on the course ID
$courseQuery = "SELECT * FROM course WHERE id = ?";
$courseStmt = $conn->prepare($courseQuery);
$courseStmt->bind_param("i", $courseId);
$courseStmt->execute();
$courseResult = $courseStmt->get_result();

if ($courseResult && $courseResult->num_rows > 0) {
    $course = $courseResult->fetch_assoc();
    $courseName = $course['name'];
    // Add more course details here as needed
} else {
    echo "Course details not found.";
    exit();
}

// Query to fetch MCQ questions from the database
$questionsQuery = "SELECT * FROM question WHERE quiz_id = (SELECT id FROM quiz WHERE course_id = ?)";
$questionsStmt = $conn->prepare($questionsQuery);
$questionsStmt->bind_param("i", $courseId);
$questionsStmt->execute();
$questionsResult = $questionsStmt->get_result();

// Calculate the total number of questions
$totalQuestions = $questionsResult->num_rows;

// Retrieve quiz result from the URL
$quizResult = isset($_GET['result']) ? (int)$_GET['result'] : 0;

// Save the quiz result in the coursetaken table
// You should have a unique identifier for the course taken by this user, replace with your actual column name
$coursetakenId = 1; // Replace with the actual coursetaken ID

// Update the coursetaken table with the quiz result
$updateResultQuery = "UPDATE coursetaken SET result = ? WHERE id = ?";
$updateResultStmt = $conn->prepare($updateResultQuery);
$updateResultStmt->bind_param("ii", $quizResult, $coursetakenId);

if ($updateResultStmt->execute()) {
    // Successfully updated the result in the database
} else {
    // Handle the case where the update fails
    echo "Failed to update quiz result in the database.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="mycourse.css"> <!-- Include your new CSS file -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link rel="stylesheet" href="mycourse.css"> <!-- Include your new CSS file -->
    <link rel="stylesheet" href="style.css"> 
    
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
                <h1>Quiz for <?= $courseName ?></h1>
                <div class="quiz-questions">
                    <form action="quizresult.php" method="post">
                        <?php
                        while ($question = $questionsResult->fetch_assoc()) {
                            echo '<div class="question">';
                            echo '<h3>' . $question['question_text'] . '</h3>';
                            echo '<ul class="choices">';
                            // Assuming you have columns option_a, option_b, option_c, option_d
                            $choices = [
                                'A' => $question['option_a'],
                                'B' => $question['option_b'],
                                'C' => $question['option_c'],
                                'D' => $question['option_d'],
                            ];
                            foreach ($choices as $key => $choice) {
                                echo '<li>';
                                echo '<input type="radio" name="question_' . $question['id'] . '" value="' . $key . '">';
                                echo '<label>' . $choice . '</label>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                        ?>
                        <input type="submit" value="Submit Quiz">
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
