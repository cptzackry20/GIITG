<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$lessonName = $lessonDescription = $lessonLink = $courseId = "";
$lessonNameErr = $lessonDescriptionErr = $lessonLinkErr = $courseIdErr = $contentFileErr = "";

// Handle the form submission to add a new lesson
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize form inputs
    $lessonName = htmlspecialchars($_POST['lesson_name']);
    $lessonDescription = htmlspecialchars($_POST['lesson_desc']);
    $lessonLink = htmlspecialchars($_POST['lesson_link']);
    $courseId = $_POST['course_id'];

    // Check if a file was uploaded
    if (!empty($_FILES['content_file']['name'])) {
        $contentFileName = $_FILES['content_file']['name'];
        $contentFileType = strtolower(pathinfo($contentFileName, PATHINFO_EXTENSION));
        $contentFileTmpName = $_FILES['content_file']['tmp_name'];
        $uploadDirectory = '../doc/doc'; // Specify the directory where you want to store uploaded files

        // Validate file type (you can add more specific validation)
        $allowedFileTypes = ['pdf', 'pptx', 'docx'];
        if (!in_array($contentFileType, $allowedFileTypes)) {
            $contentFileErr = 'Invalid file type. Only PDF, PPTX, and DOCX files are allowed.';
        }

        // Move the uploaded file to the upload directory
        $newContentFilePath = $uploadDirectory . uniqid() . '.' . $contentFileType; // Generate a unique file name
        if (move_uploaded_file($contentFileTmpName, $newContentFilePath)) {
            // File uploaded successfully
        } else {
            $contentFileErr = 'File upload failed. Please try again.';
        }
    }

    // Validate form inputs (you can add more specific validation rules)
    if (empty($lessonName)) {
        $lessonNameErr = "Lesson name is required";
    }

    if (empty($lessonDescription)) {
        $lessonDescriptionErr = "Lesson description is required";
    }

    if (empty($lessonLink)) {
        $lessonLinkErr = "Lesson link is required";
    }

    if (empty($courseId)) {
        $courseIdErr = "Course is required";
    }

    // Check if there are no errors
    if (empty($lessonNameErr) && empty($lessonDescriptionErr) && empty($lessonLinkErr) && empty($courseIdErr) && empty($contentFileErr)) {
        // Retrieve the course_name based on the selected course_id
        $courseQuery = "SELECT name FROM course WHERE id = ?";
        $stmt = $conn->prepare($courseQuery);

        // Check if the prepare statement was successful
        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        // Bind parameters
        $stmt->bind_param("i", $courseId);

        // Execute the query
        $stmt->execute();

        // Bind the result variable
        $stmt->bind_result($courseName);

        // Fetch the result
        $stmt->fetch();

        // Close the statement
        $stmt->close();

        // Insert the new lesson into the database with the course_name, content_file, and content_type
        $insertQuery = "INSERT INTO lesson (name, `desc`, link, course_id, course_name, content_file, content_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        // Check if the prepare statement was successful
        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }

        // Determine the content type based on the file extension
        $contentType = '';
        switch ($contentFileType) {
            case 'pdf':
                $contentType = 'pdf';
                break;
            case 'pptx':
                $contentType = 'pptx';
                break;
            case 'docx':
                $contentType = 'docx';
                break;
            // Add more cases for other file types if needed
        }

        // Bind parameters
        $stmt->bind_param("sssisss", $lessonName, $lessonDescription, $lessonLink, $courseId, $courseName, $newContentFilePath, $contentType);

        // Check if the bind_param was successful
        if ($stmt->execute()) {
            // Lesson added successfully
            header("Location: managelesson.php"); // Redirect to the manage lessons page
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

// Query to fetch course names for the select option
$courseQuery = "SELECT id, name FROM course";
$courseResult = $conn->query($courseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary meta tags, stylesheets, and scripts -->
    <title>Add New Lesson</title>
    <script src="https://cdn.tiny.cloud/1/a18et7yc09kx92w87yin53oj1w3djuz0ibkkvu404v0t3tea/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/course.css">
    <script src="../style/tinymce/js/tinymce.min.js" referrerpolicy="origin"></script>
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
<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Add New Lesson</h1>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <br>
        <h2>Add New Lesson</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="lesson_name">Lesson Name</label>
                <input type="text" class="form-control" id="lesson_name" name="lesson_name" required>
                <span class="text-danger"><?php echo $lessonNameErr; ?></span>
            </div>
            <div class="form-group">
                <label for="lesson_desc">Lesson Description</label>
                <!-- Hidden textarea to store TinyMCE content -->
                <textarea id="lesson_desc" name="lesson_desc" style="display:none;"></textarea>
                <!-- Actual TinyMCE editor -->
                <div id="lesson_desc_editor"></div>
                <span class="text-danger"><?php echo $lessonDescriptionErr; ?></span>
            </div>
            <div class="form-group">
                <label for="lesson_link">Lesson Link</label>
                <input type="text" class="form-control" id="lesson_link" name="lesson_link" required>
                <span class="text-danger"><?php echo $lessonLinkErr; ?></span>
            </div>
            <div class="form-group">
                <label for="content_file">Upload File</label>
                <input type="file" class="form-control-file" id="content_file" name="content_file">
                <span class="text-danger"><?php echo $contentFileErr; ?></span>
            </div>
            <div class="form-group">
                <label for="course_id">Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="">Select a course</option>
                    <?php
                    if ($courseResult && $courseResult->num_rows > 0) {
                        while ($courseRow = $courseResult->fetch_assoc()) {
                            echo '<option value="' . $courseRow['id'] . '">' . $courseRow['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
                <span class="text-danger"><?php echo $courseIdErr; ?></span>
            </div>
            <button type="submit" class="btn btn-primary">Add Lesson</button>
        </form>
        <br>
    </div>

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
    <script>
    // Initialize TinyMCE on the lesson description textarea
    tinymce.init({
        selector: '#lesson_desc_editor', // Use the div as the selector
        height: 300, // Set the height of the editor
        plugins: 'advlist autolink lists link image charmap print preview anchor textcolor',
        toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        menubar: false,
    });

    // Add an event listener to update the hidden textarea with TinyMCE content before form submission
    document.querySelector('form').addEventListener('submit', function () {
        var content = tinymce.get('lesson_desc_editor').getContent();
        document.querySelector('#lesson_desc').value = content;
    });
    </script>
</body>
</html>
