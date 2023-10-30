<?php
    // Include the database configuration file
    include '../includes/config.php';

    // Handle the form submission to add a new course
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $courseName = $_POST['course_name'];
        $courseDesc = $_POST['course_desc'];
        $courseAuthor = $_POST['course_author'];
        $courseDuration = $_POST['course_duration'];

        // You can add validation and sanitization here as needed

        // Upload and save the course image
        $targetDirectory = "img/courseimg/"; // Specify the target directory
        $targetFile = $targetDirectory . basename($_FILES["course_image"]["name"]); // Get the target file path

        // Check if the image file is a real image
        if (getimagesize($_FILES["course_image"]["tmp_name"]) === false) {
            echo "Error: Invalid image file.";
            exit;
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["course_image"]["tmp_name"], $targetFile)) {
            // Insert the new course into the database with the image path
            $insertQuery = "INSERT INTO course (name, `desc`, author, duration, img) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);

            // Check if the prepare statement was successful
            if ($stmt === false) {
                echo "Error: " . $conn->error;
                exit;
            }

            // Bind parameters
            $stmt->bind_param("sssss", $courseName, $courseDesc, $courseAuthor, $courseDuration, $targetFile);

            // Check if the bind_param was successful
            if ($stmt->execute()) {
                // Course added successfully
                header("Location: dashboard.php"); // Redirect to the dashboard page
                exit;
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error uploading image.";
        }
    }
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Add New Course</title>
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

    <body>
        <?php include '../includes/adminnavbar.php'; ?>
        
        <div class="section web-header">
            <div class="header-container">
                <div class="header-content">
                    <h1>Add New Course</h1>
                </div>
            </div>
        </div>
        <div class="container mt-10">
            <br>
            <h2>Add New Course</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="course_name">Course Name</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required>
                </div>
                <div class="form-group">
                    <label for="course_desc">Course Description</label>
                    <textarea class="form-control" id="course_desc" name="course_desc" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="course_author">Course Author</label>
                    <input type="text" class="form-control" id="course_author" name="course_author" required>
                </div>
                <div class="form-group">
                    <label for="course_duration">Course Duration</label>
                    <input type="text" class="form-control" id="course_duration" name="course_duration" required>
                </div>
                <div class="form-group">
                    <label for="course_image">Course Image</label>
                    <input type="file" class="form-control-file" id="course_image" name="course_image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Course</button>
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
    </body>

    </html>
