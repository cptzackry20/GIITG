<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

// Start the session at the beginning
session_start();

// Check if the user is logged in (staff member)
if (!isset($_SESSION['user'])) {
    // Redirect to the login page or display an error message
    header("Location: login.php"); // Replace with the correct login page URL
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $staffId = $_POST['staff_id'];
    $feedback = $_POST['feedback'];
    $rating = $_POST['rating'];
    $courseTakenId = $_POST['course_id'];

    // Insert feedback into the database
    $insertQuery = "INSERT INTO feedback (staff_id, comment, course_taken_id, rating) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertQuery);

    // Check if the statement was prepared successfully
    if ($insertStmt) {
        $insertStmt->bind_param("issi", $staffId, $feedback, $courseTakenId, $rating);

        if ($insertStmt->execute()) {
            // Feedback successfully inserted
            header("Location: feedbackcourse.php?course_id=$courseTakenId&feedback_success=1");
            exit();
        } else {
            // Handle the case where feedback insertion fails
            echo "Feedback submission failed. Please try again.";
        }
    } else {
        // Handle the case where the statement preparation fails
        echo "Statement preparation failed: " . $conn->error;
    }
} else {
    // Handle the case where the script is accessed without a POST request
    echo "Invalid request.";
}
?>
