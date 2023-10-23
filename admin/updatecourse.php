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
        $targetDir = "path_to_existing_image_folder/"; // Update with your actual image folder path
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
                                                    img = '$newFileName', 
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
?>
