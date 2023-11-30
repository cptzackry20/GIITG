<?php
session_start(); // Ensure session is started

include 'includes/config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your feedback submission logic here

    if (!isset($_SESSION['user'])) {
        // Redirect to the login page if the user is not logged in
        header("Location: login.php");
        exit;
    }

    // Get the comment from the form
    $comment = $_POST["comment"];
    
    // Assuming 'id' is the staff ID field in your user data
    $staff_id = $_SESSION['user']['id'];

    // Perform any necessary validation on the comment (e.g., length checks, sanitization)

    // Store the comment and staff ID in the feedback table
    $sql = "INSERT INTO feedback (comment, staff_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind the comment and staff ID to the SQL statement
    $stmt->bind_param("si", $comment, $staff_id); // 's' for string and 'i' for integer

    // Execute the statement
    if ($stmt->execute()) {
        // Comment added successfully
        // Redirect back to feedback.php
        header("Location: feedback.php");
        exit();
    } else {
        // Comment insertion failed
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If the form is not submitted, redirect to the feedback page
    header("Location: feedback.php");
    exit();
}
?>
