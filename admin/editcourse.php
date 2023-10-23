<?php
// Include the database configuration file
include '../includes/config.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $courseId = $_POST['course_id'];
    $courseName = $_POST['course_name'];
    $courseDesc = $_POST['course_desc'];
    $courseAuthor = $_POST['course_author'];
    $courseDuration = $_POST['course_duration'];

    // Check if a new image file is uploaded
    if (isset($_FILES['course_img']['name']) && $_FILES['course_img']['name'] != "") {
        // Define the path to store the uploaded image
        $targetDir = "img/courseimg/"; // Update with your actual image folder path
        $targetFile = $targetDir . basename($_FILES['course_img']['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the uploaded file is a valid image
        $check = getimagesize($_FILES['course_img']['tmp_name']);
        if ($check !== false) {
            // Allow only specific image file formats (you can customize this list)
            $allowedFormats = ["jpg", "jpeg", "png", "gif"];
            if (in_array($imageFileType, $allowedFormats)) {
                // Generate a unique filename to avoid overwriting existing files
                $newFileName = "course_" . uniqid() . "." . $imageFileType;
                $newFilePath = $targetDir . $newFileName;

                // Move the uploaded image to the destination folder
                if (move_uploaded_file($_FILES['course_img']['tmp_name'], $newFilePath)) {
                    // Update the course information in the database with the new image filename
                    $updateQuery = "UPDATE course SET name = '$courseName', 
                                                    `desc` = '$courseDesc', 
                                                    author = '$courseAuthor', 
                                                    img = '$newFilePath', 
                                                    duration = '$courseDuration' 
                                    WHERE id = $courseId";

                    if ($conn->query($updateQuery) === TRUE) {
                        echo "Course updated successfully.";
                    } 
                    
                    else {
                        echo "Error updating course: " . $conn->error;
                    }
                } else {
                    echo "Error uploading image.";
                }
            } else {
                echo "Invalid image file format. Allowed formats: jpg, jpeg, png, gif.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        // No new image uploaded, update course information without changing the image
        $updateQuery = "UPDATE course SET name = '$courseName', 
                                        `desc` = '$courseDesc', 
                                        author = '$courseAuthor', 
                                        duration = '$courseDuration' 
                        WHERE id = $courseId";

        if ($conn->query($updateQuery) === TRUE) {
            echo "Course updated successfully.";
    
            // Redirect to dashboard.php
            header("Location: dashboard.php");
            exit(); // Make sure to exit to prevent further execution
        } else {
            echo "Error updating course: " . $conn->error;
        }
    }
}

// Fetch course details from the database
if (isset($_GET['id'])) {
    $courseId = $_GET['id'];

    $query = "SELECT * FROM course WHERE id = $courseId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $courseName = $row['name'];
        $courseDesc = $row['desc'];
        $courseAuthor = $row['author'];
        $courseImg = $row['img']; // Retrieve the image URL from the database
        $courseDuration = $row['duration'];
    } else {
        echo "Course not found.";
        exit;
    }
} else {
    echo "Course ID not provided.";
    exit;
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
<body>
<?php include '../includes/adminnavbar.php'; ?>

    <div class="container mt-5">
        <h2>Edit Course</h2>

        <!-- Display the existing course image -->
        <p>Existing Course Image:</p>
        <img src="../<?php echo $courseImg; ?>" alt="Course Image" width="150">

        <!-- Create the course edit form -->
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
            
            <div class="form-group">
                <label for="course_name">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo $courseName; ?>">
            </div>
            
            <div class="form-group">
                <label for="course_desc">Course Description</label>
                <textarea class="form-control" id="course_desc" name="course_desc" rows="4"><?php echo $courseDesc; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="course_author">Course Author</label>
                <input type="text" class="form-control" id="course_author" name="course_author" value="<?php echo $courseAuthor; ?>">
            </div>
            
            <div class="form-group">
                <label for="course_img">Change Course Image (Optional)</label>
                <input type="file" class="form-control-file" id="course_img" name="course_img">
            </div>
            
            <div class="form-group">
                <label for="course_duration">Course Duration</label>
                <input type="text" class="form-control" id="course_duration" name="course_duration" value="<?php echo $courseDuration; ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
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
