<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the course ID, staff ID, and finish status from the POST data
    $courseId = $_POST['course_id'];
    $staffId = $_POST['staff_id'];
    $finishStatus = $_POST['finish'];

    // Query to update the course finish status in the coursetaken table
    $updateQuery = "UPDATE coursetaken SET finish = ? WHERE staff_id = ? AND course_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("iii", $finishStatus, $staffId, $courseId);

    if ($updateStmt->execute()) {
        // Course finish status updated successfully
        http_response_code(200);
    } else {
        // Failed to update the course finish status
        http_response_code(500); // Internal Server Error
        echo "Failed to update course finish status.";
    }
} else {
    // If the request method is not POST, respond with an error
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method.";
}
?>
