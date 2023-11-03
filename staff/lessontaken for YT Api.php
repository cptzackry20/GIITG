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
} else {
    // Handle the case where the staff member's details are not found
    // You can redirect to an error page or display an error message
    echo "Staff details not found.";
    exit();
}

// Get the lesson ID from the URL
$lessonId = $_GET['lesson_id'];

// Query to fetch lesson details based on the lesson ID
$lessonQuery = "SELECT lesson.*, course.name as course_name
               FROM lesson
               INNER JOIN course ON lesson.course_id = course.id
               WHERE lesson.id = ?";
$lessonStmt = $conn->prepare($lessonQuery);
$lessonStmt->bind_param("i", $lessonId);
$lessonStmt->execute();
$lessonResult = $lessonStmt->get_result();

if ($lessonResult && $lessonResult->num_rows > 0) {
    $lesson = $lessonResult->fetch_assoc();

    // Replace the following placeholders with actual data from the database
    $courseName = $lesson['course_name'];
    $lessonName = $lesson['name'];
    $lessonDescription = $lesson['desc'];
    $lessonLink = $lesson['link']; // Update with the YouTube video URL
    $lessonContentFile = $lesson['content_file'];
    $lessonContentType = $lesson['content_type'];
} else {
    // Handle the case where the lesson details are not found
    // You can redirect to an error page or display an error message
    echo "Lesson details not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="lessontaken.css"> <!-- Include your new CSS file -->
    <style>
        /* Add your additional styles here */
        /* Style for the video container */
        .video-container {
            position: relative;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
            overflow: hidden;
        }
    </style>
    <script src="https://www.youtube.com/iframe_api"></script> <!-- Include the YouTube Iframe API -->

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lesson Details</title>
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
                <!-- Add your lesson details here -->
                <h1>Lesson Details</h1>
                <div class="course-details">
                    <h2>Course: <?= $courseName ?></h2>
                </div>

                <!-- Add your video here -->
                <h2>Lesson Video</h2>
                <div class="video-container">
                    <div id="player"></div> <!-- Container for YouTube video player -->
                </div>

                <!-- Add your lesson details here -->
                <h2><?= $lessonName ?></h2>
                <?php echo htmlspecialchars_decode($lessonDescription); ?></p>

                <!-- Add your content specific to the lesson -->
                <h2>Lesson Content</h2>
                <?php
                if ($lessonContentType === 'pdf') {
                    echo '<embed src="' . $lessonContentFile . '" type="application/pdf" width="100%" height="500px">';
                } elseif ($lessonContentType === 'video') {
                    echo '<video width="100%" controls>';
                    echo '<source src="' . $lessonContentFile . '" type="video/mp4">';
                    echo 'Your browser does not support the video tag.';
                    echo '</video>';
                }
                ?>

                <!-- Add a link to go back to the course page -->
                <a href="coursetaken.php?course_id=<?= $lesson['course_id'] ?>">Back to Course</a>
            </div>
        </div>
    </div>
    <script>
        var hamburger = document.querySelector(".hamburger");
        hamburger.addEventListener("click", function () {
            document.querySelector("body").classList.toggle("active");
        });

        
var player;

function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: '360',
        width: '640',
        videoId: 'YOUR_VIDEO_ID', // Replace with the actual video ID
        playerVars: {
            controls: 0,
            disablekb: 1,
            modestbranding: 1,
            loop: 1,
            autoplay: 1
        },
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

function onPlayerReady(event) {
    event.target.playVideo();
    // Capture the start timestamp and send it to your server
    var startTimestamp = getCurrentTimestamp();
    updateTimestamp('start', startTimestamp);
}

function onPlayerStateChange(event) {
    if (event.data == YT.PlayerState.ENDED) {
        // Video has ended, capture the finish timestamp and send it to your server
        var finishTimestamp = getCurrentTimestamp();
        updateTimestamp('finish', finishTimestamp);
        event.target.seekTo(0); // Restart the video
    }
}

function getCurrentTimestamp() {
    return new Date().toISOString();
}

function updateTimestamp(type, timestamp) {
    // Send an AJAX request to your server to update the timestamp in the database
    var courseId = <?= $courseId ?>;
    var lessonId = <?= $lessonId ?>;
    var staffId = <?= $staffID ?>;
    var url = 'update_timestamp.php'; // Create a PHP script to handle the update
    var params = 'course_id=' + courseId + '&lesson_id=' + lessonId + '&staff_id=' + staffId + '&type=' + type + '&timestamp=' + timestamp;
    var xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status === 200) {
            // Timestamp updated successfully
        } else {
            // Handle errors
            console.error('Failed to update timestamp.');
        }
    };

    xhr.send(params);
}
    </script>
</body>
</html>
