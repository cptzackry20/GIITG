<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

// Start the session at the beginning
session_start();

// Fetch staff details from the database based on the user's session information
$staffID = $_SESSION['user']['id']; // Assuming you store the user's ID in the session

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
    // Handle the case where the staff member's details are not found
    // You can redirect to an error page or display an error message
    echo "Staff details not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="mycourse.css">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Course</title>
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
                <h1>Courses Taken</h1>
                <div class="course-container">
                <?php
while ($course = $coursesResult->fetch_assoc()) {
    // Display course details
    echo '<div class="course-card">';
    echo '<img src="../' . $course['img'] . '" alt="Course Image">';
    echo '<h2>' . $course['name'] . '</h2>';
    echo '<p>' . $course['desc'] . '</p>';
    echo '<p>Duration: ' . $course['duration'] . '</p>';

    // Add a "Start" button for each course with the class name
    echo '<a class="start-button" href="coursetaken.php?course_id=' . $course['id'] . '">Start</a>';

    echo '</div>';
}
?>

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
