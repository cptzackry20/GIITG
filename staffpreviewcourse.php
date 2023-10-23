<?php
// Include the navbar before starting the session
include 'includes/config.php';

// Initialize variables
$courseId = 0;
$courseName = "";
$courseDesc = "";
$courseAuthor = "";
$courseImg = "";
$courseDuration = "";
$lessons = array();
$userEnrolled = false; // Flag to track user enrollment status

// Check if the course ID is provided in the URL
if (isset($_GET['course_id'])) {
    $courseId = $_GET['course_id'];

    // Retrieve course details from the database using $courseId
    $courseQuery = "SELECT * FROM course WHERE id = $courseId";
    $courseResult = $conn->query($courseQuery);

    if ($courseResult && $courseResult->num_rows > 0) {
        $courseData = $courseResult->fetch_assoc();
        $courseName = $courseData['name'];
        $courseDesc = $courseData['desc'];
        $courseAuthor = $courseData['author'];
        $courseImg = $courseData['img'];
        $courseDuration = $courseData['duration'];
    }

    // Retrieve a brief list of lessons for the current course
    $lessonQuery = "SELECT id, name, link FROM lesson WHERE course_id = $courseId";
    $lessonResult = $conn->query($lessonQuery);

    if ($lessonResult && $lessonResult->num_rows > 0) {
        while ($lessonData = $lessonResult->fetch_assoc()) {
            $lessonId = $lessonData['id'];
            $lessonName = $lessonData['name'];
            $lessonLink = $lessonData['link'];
            $lessons[] = array('id' => $lessonId, 'name' => $lessonName, 'link' => $lessonLink);
        }
    }

    // Check if the user is enrolled in this course
    if (isset($_SESSION['user_id'])) { // Assuming you have a user authentication system with a 'user_id' session variable
        $userId = $_SESSION['user_id'];
        $enrollmentQuery = "SELECT * FROM coursetaken WHERE user_id = $userId AND course_id = $courseId";
        $enrollmentResult = $conn->query($enrollmentQuery);

        if ($enrollmentResult && $enrollmentResult->num_rows > 0) {
            $userEnrolled = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Preview Course</title>
    <link rel="stylesheet" href="style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/course.css">
    <link rel="stylesheet" href="style/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<div class="section web-header">
    <div class="header-container">
        <div class="header-content">
            <h1><?php echo $courseName; ?></h1>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2>Course Details</h2>
    <div class="row">
        <div class="col-md-6">
            <img src="<?php echo $courseImg; ?>" alt="Course Image" class="img-fluid">
        </div>
        <div class="col-md-6">
            <p><?php echo $courseDesc; ?></p>
            <p><strong>Author: <?php echo $courseAuthor; ?></strong></p>
            <p><strong>Duration: <?php echo $courseDuration; ?></strong></p>
        </div>
    </div>

    <h2>Course Lessons</h2>
    <ul>
        <?php foreach ($lessons as $lesson) { ?>
            <li>
                <strong><?php echo $lesson['name']; ?>:</strong>
                <a href="courses/<?php echo $lesson['link']; ?>" target="_blank">Watch Lesson</a>
            </li>
        <?php } ?>
    </ul>

    <div class="modal-footer">
        <!-- Display "Enroll Me" button if the user is not enrolled -->
        <?php if (!$userEnrolled) { ?>
            <form method="post" action="enroll.php">
                <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                <button type="submit" name="enroll" class="btn btn-primary">Enroll Me</button>
            </form>
        <?php } else { ?>
            <!-- Display a message if the user is already enrolled -->
            <p>You are already enrolled in this course.</p>
        <?php } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
    integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
    crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
</body>
</html>
