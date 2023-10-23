<?php
session_start();

// Include the database configuration file
include 'includes/config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the comment from the form
    $comment = $_POST["comment"];
    
    // In a real-world scenario, you would have a way to identify the currently logged-in staff member.
    // For this example, I'll assume you have a session variable 'staff_id' that stores the staff's ID.

    if (isset($_SESSION['staff_id'])) {
        $staff_id = $_SESSION['staff_id'];
    } else {
        // If the 'staff_id' session variable is not set, handle it appropriately, e.g., redirect to the login page.
        // You should replace this with your actual logic.
        header("Location: login.php");
        exit();
    }

    // Perform any necessary validation on the comment (e.g., length checks, sanitization)

    // Store the comment and staff ID in the feedback table
    $sql = "INSERT INTO feedback (comment, staff_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind the comment and staff ID to the SQL statement
    $stmt->bind_param("si", $comment, $staff_id); // 's' for string and 'i' for integer

    // Execute the statement
    if ($stmt->execute()) {
        // Comment added successfully
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