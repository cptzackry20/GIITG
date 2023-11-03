<?php
// Include necessary files and database connection here
include 'includes/config.php'; // Adjust the path as needed

// Define the staff and course for which you want to generate lesson records
$staffId = 2; // Change to the desired staff ID
$courseId = 1; // Change to the desired course ID

// Query to fetch all lessons for the specified course
$lessonsQuery = "SELECT id FROM lesson WHERE course_id = ?";
$lessonsStmt = $conn->prepare($lessonsQuery);
$lessonsStmt->bind_param("i", $courseId);
$lessonsStmt->execute();
$lessonsResult = $lessonsStmt->get_result();

// Check if there are lessons for the course
if ($lessonsResult && $lessonsResult->num_rows > 0) {
    while ($lesson = $lessonsResult->fetch_assoc()) {
        $lessonId = $lesson['id'];
        $timestampStart = date('Y-m-d H:i:s'); // Current timestamp
        $timestampFinish = null; // Staff member hasn't finished the lesson yet

        // Insert a new record into the lessontaken table
        $insertQuery = "INSERT INTO lessontaken (coursetaken_id, staff_id, course_id, lesson_id, timestamp_start, timestamp_finish, total_time_minutes) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("iiisssi", $coursetakenId, $staffId, $courseId, $lessonId, $timestampStart, $timestampFinish, $totalTimeMinutes);

        // Set the coursetaken ID based on the staff, course, and lesson
        $coursetakenId = generateCoursetakenId($staffId, $courseId);

        // Initialize total_time_minutes to 0
        $totalTimeMinutes = 0;

        $insertStmt->execute();
        $insertStmt->close();
    }
} else {
    echo "No lessons found for the specified course.";
}

// Function to generate coursetaken ID based on staff, course, and lesson
function generateCoursetakenId($staffId, $courseId) {
    return $staffId . '_' . $courseId;
}
?>
