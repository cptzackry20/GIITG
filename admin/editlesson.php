<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$lessonId = 0;
$lessonName = "";
$lessonDesc = "";
$lessonLink = "";
$courseId = 0;
$contentFile = ""; // Added variable for content file

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
            $courseId = $row['course_id'];
            $courseName = $row['course_name'];
            $contentFile = $row['content_file']; // Retrieve content file
        } else {
            echo "Lesson not found.";
            exit;
        }
    } else {
        echo "Error: " . $conn->error;
        exit;
    }
}

// Query to fetch all course names for the dropdown list
$courseQuery = "SELECT id, name FROM course";
$courseResult = $conn->query($courseQuery);
$courseOptions = array();

if ($courseResult && $courseResult->num_rows > 0) {
    while ($courseRow = $courseResult->fetch_assoc()) {
        $courseOptions[$courseRow['id']] = $courseRow['name'];
    }
}

// Handle the form submission to update lesson information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lessonName = $_POST['lesson_name'];
    $lessonDesc = $_POST['lesson_desc'];
    $lessonLink = $_POST['lesson_link'];
    $courseId = $_POST['course_id'];

    // You can add validation and sanitization here as needed

    // Update lesson information in the database
    $updateQuery = "UPDATE lesson SET name = ?, `desc` = ?, link = ?, course_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    // Check if the prepare statement was successful
    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    // Bind parameters
    $stmt->bind_param("ssssi", $lessonName, $lessonDesc, $lessonLink, $courseId, $lessonId);

    // Check if the bind_param was successful
    if ($stmt->execute()) {
        // Lesson updated successfully
        header("Location: managelesson.php"); // Redirect to the manage lessons page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Edit Course</title>
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
    </style>
</head>
</head>

<body>
<?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Edit Lesson</h1>
            </div>
        </div>
    </div>

    <div class="container mt-10">
        <br>
        <h2>Edit Lesson</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="lesson_name">Lesson Name</label>
                <input type="text" class="form-control" id="lesson_name" name="lesson_name" value="<?php echo $lessonName; ?>"
                    required>
            </div>
            <div class="form-group">
                <label for="lesson_desc">Lesson Description</label>
                <!-- Add a textarea for lesson description with an ID -->
                <textarea class="form-control" id="lesson_desc" name="lesson_desc" rows="4"
                    required><?php echo $lessonDesc; ?></textarea>
            </div>
            <div class="form-group">
                <label for="lesson_link">Lesson Link (External Link)</label>
                <input type="url" class="form-control" id="lesson_link" name="lesson_link"
                    value="<?php echo $lessonLink; ?>" required>
            </div>
            <div class="form-group">
                <label for="course_id">Course Name</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <?php
                    foreach ($courseOptions as $optionId => $optionName) {
                        // Check if this option matches the current course ID
                        $selected = ($optionId == $courseId) ? 'selected' : '';
                        echo "<option value=\"$optionId\" $selected>$optionName</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <div class="video-container">
                    <!-- Embedded video player -->
                    <iframe class="video-embed" src="<?php echo $lessonLink; ?>" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <div class="form-group">
            <!-- Add this code to display the "Download Content File" button -->
            <label for="content_file">Content File</label> <br>
            <a href="<?php echo $contentFile; ?>" download class="btn btn-info">Download Content File</a>
            </div>

            <button type="submit" class="btn btn-primary">Update Lesson</button>
        </form>
        <br>
    </div>

    <?php include '../includes/footer.php'; ?>

    <!-- Include TinyMCE library and initialize it on the lesson description textarea -->
    <script src="https://cdn.tiny.cloud/1/a18et7yc09kx92w87yin53oj1w3djuz0ibkkvu404v0t3tea/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#lesson_desc', // Use the ID of your textarea here
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor textcolor',
            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            menubar: false,
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script>
        tinymce.init({
            selector: '#lesson_desc', // Use the ID of your textarea here
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor textcolor',
            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            menubar: false,
        });
    </script>

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
