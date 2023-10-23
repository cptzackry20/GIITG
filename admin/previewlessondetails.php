<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$lessonId = 0;
$lessonName = "";
$lessonDesc = "";
$lessonLink = "";
$courseName = "";
$contentFile = ""; // Add this variable to store content file path

// Check if lesson ID is provided in the URL
if (isset($_GET['id'])) {
    $lessonId = $_GET['id'];

    // Retrieve lesson details from the database, including the associated course name and content file
    $selectQuery = "SELECT lesson.*, course.name AS course_name FROM lesson JOIN course ON lesson.course_id = course.id WHERE lesson.id = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("i", $lessonId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $lessonName = $row['name'];
            $lessonDesc = $row['desc'];
            $lessonLink = $row['link'];
            $courseName = $row['course_name'];
            $contentFile = $row['content_file']; // Retrieve content file path
        } else {
            echo "Lesson not found.";
            exit;
        }
    } else {
        echo "Error: " . $conn->error;
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Preview Lesson</title>
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/course.css">
    <style>
        /* Add dark background color to the navbar */
        .navbar {
            background-color: #333;
        }

        /* Style the navbar text and links for better visibility on dark background */
        .navbar-dark .navbar-nav .nav-link {
            color: #fff;
        }

        /* Style the active link */
        .navbar-dark .navbar-nav .nav-item.active .nav-link {
            color: #fff;
            font-weight: bold;
        }

        /* Style the embedded video container */
        .video-container {
            position: relative;
            padding-bottom: 26.25%; /* 16:9 aspect ratio */
            height: 0;
        }

        .video-embed {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* Style the content file download button */
        .download-button {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Preview Lesson</h1>
            </div>
        </div>
    </div>

    <div class="container mt-10">
    <br>
    <div class="text-center"> <!-- Center-align these elements -->
        <h2>Lesson: <?php echo $lessonName; ?></h2>
        <p><strong>Course: <?php echo $courseName; ?></strong></p>
    </div>
    <!-- Use htmlspecialchars_decode to render HTML content correctly -->
   
    <!-- Display the embedded video -->
    <div class="video-container">
        <iframe class="video-embed" src="<?php echo $lessonLink; ?>" frameborder="0" allowfullscreen></iframe>
    </div>
    <?php if (!empty($contentFile)) { ?>
        <a href="<?php echo $contentFile; ?>" download class="btn btn-info download-button">Download Content File</a>
    <?php } ?><br>
    <?php echo htmlspecialchars_decode($lessonDesc); ?>

    <!-- Add a download button for the content file if available -->
    
</div><br>


    <?php include '../includes/footer.php'; ?>

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
