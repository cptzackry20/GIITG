<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start(); // Ensure session is started

include 'includes/config.php';

// Check if lesson ID is provided in the URL
if (isset($_GET['lesson_id'])) {
    $lessonId = $_GET['lesson_id'];

    // Ensure $_SESSION['lesson_start_time'] is an array
    if (!isset($_SESSION['lesson_start_time']) || !is_array($_SESSION['lesson_start_time'])) {
        $_SESSION['lesson_start_time'] = array();
    }

    // Check if this is the first time the user accesses the lesson
    if (!isset($_SESSION['lesson_start_time'][$lessonId])) {
        $_SESSION['lesson_start_time'][$lessonId] = time();
    }

    // Retrieve lesson details from the database using $lessonId
    $lessonQuery = "SELECT * FROM lesson WHERE id = $lessonId";
    $lessonResult = $conn->query($lessonQuery);
    
    if ($lessonResult && $lessonResult->num_rows > 0) {
        $lessonData = $lessonResult->fetch_assoc();
        $lessonName = $lessonData['name'];
        $pdfFilePath = $lessonData['content_file'];
    
        // Remove "../" from the file path
        $cleanedFilePath = preg_replace('/\.\.\//', '', $pdfFilePath);
    
        // Display the lesson name
        echo '<h2>' . $lessonName . ' Preview</h2>';
    
        // Display the PDF using an <iframe>
        echo '<iframe src="' . $cleanedFilePath . '" width="100%" height="600"></iframe>';
        
        // Calculate and display the elapsed time with JavaScript countdown
        echo '<p id="countdown"></p>';
        echo '<script>
            var lessonStartTime = ' . $_SESSION['lesson_start_time'][$lessonId] . ';
            var countdownElement = document.getElementById("countdown");
    
            function updateCountdown() {
                var currentTime = Math.floor(Date.now() / 1000);
                var elapsedSeconds = currentTime - lessonStartTime;
                var remainingSeconds = 600 - elapsedSeconds; // 600 seconds = 10 minutes
    
                var minutes = Math.floor(remainingSeconds / 60);
                var seconds = remainingSeconds % 60;
    
                countdownElement.innerHTML = "Time spent on this lesson: " + minutes + " minutes " + seconds + " seconds";
    
                // Update every second
                setTimeout(updateCountdown, 1000);
            }
    
            updateCountdown();
        </script>';
    
        // Optional: You can store the elapsed time in the database for future analysis
    } else {
        echo 'Lesson not found.';
    }
} else {
    echo 'Lesson ID not provided.';
}
?>
