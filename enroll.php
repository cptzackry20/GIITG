<?php
session_start();
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the course_id and staff_id from the POST data
    $courseId = $_POST['course_id'];
    
    // Check if the staff is logged in (staff ID is set in the session)
    if (isset($_SESSION['user']['id'])) {
        $staffId = $_SESSION['user']['id']; // Corrected session variable
        
        // Check if the staff member is already enrolled in the course
        $checkQuery = "SELECT * FROM coursetaken WHERE course_id = ? AND staff_id = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ii", $courseId, $staffId);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Staff member is already enrolled, show a message
            echo '<script>';
            echo 'alert("You are already enrolled in this course.");';
            echo 'window.location.href = "course.php";'; // Redirect to the relevant page
            echo '</script>';
        } else {
            // Staff member is not enrolled, insert a new row into coursetaken
            $insertQuery = "INSERT INTO coursetaken (staff_id, course_id, status, order_date) VALUES (?, ?, 'Enrolled', NOW())";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ii", $staffId, $courseId);

            if ($insertStmt->execute()) {
                // Enrollment successful, show a message
                echo '<script>';
                echo 'alert("Enrollment successful!");';
                echo 'window.location.href = "staff/staffprofile.php";'; // Redirect to the relevant page
                echo '</script>';
            } else {
                // Enrollment failed, show a message
                echo '<script>';
                echo 'alert("Enrollment failed. Please try again later.");';
                echo 'window.location.href = "index.php";'; // Redirect to the relevant page
                echo '</script>';
            }
        }
    } else {
        // Not logged in, show a message and redirect to a login page or homepage
        echo '<script>';
        echo 'alert("You are not logged in.");';
        echo 'window.location.href = "login.php";'; // Redirect to the login page or homepage
        echo '</script>';
    }
} else {
    // Invalid request, show a message and redirect to a relevant page
    echo '<script>';
    echo 'alert("Invalid request.");';
    echo 'window.location.href = "staff/staffprofile.php";'; // Redirect to the relevant page
    echo '</script>';
}
?>
