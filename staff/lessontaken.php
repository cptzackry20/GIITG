<?php
// Include necessary files and database connection here
include '../includes/config.php'; // Adjust the path as needed

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
    $lessonLink = $lesson['link'];
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
    <link rel="stylesheet" href="lessontaken.css">
    <script data-main="js/require-config" src="js/require.js"></script>
    <script data-main="js/lesson-main" src="js/require.js"></script>

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
                    <!-- Use the video tag with custom controls -->
                    <video id="lessonVideo">
                        <source src="<?= $lessonLink ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>

                <!-- Video controls -->
                <div class="video-controls">
                    <button id="playPauseBtn" class="play-pause-btn"><i class="fas fa-play"></i></button>
                </div><br>
                <div class="progress-text">Your View Map: <span id="progressPercentage">0%</span></div>
               <br> </div>
                <div class="video-progress" id="videoProgress">
                    <div class="progress-bar"></div>
                    
                   
               
<br>
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
        document.addEventListener('DOMContentLoaded', function () {
            var video = document.getElementById('lessonVideo');
            var progressBar = document.getElementById('videoProgress');
            var progress = document.querySelector('.progress-bar');
            var progressPercentage = document.getElementById('progressPercentage');
            var playPauseBtn = document.getElementById('playPauseBtn');

            playPauseBtn.addEventListener('click', function () {
                if (video.paused || video.ended) {
                    video.play();
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    video.pause();
                    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                }
            });

            video.addEventListener('timeupdate', function () {
                var value = (video.currentTime / video.duration) * 100;
                progress.style.width = value + '%';
                progressPercentage.textContent = value.toFixed(2) + '%';
            });

            progressBar.addEventListener('click', function (e) {
                e.preventDefault();
            });

            video.addEventListener('seeking', function () {
                // Disable seeking by resetting the video to the current time
                video.currentTime = video.currentTime;
            });
        });
    </script>
</body>

</html>
