<?php
// Include the database configuration file
include '../includes/config.php';

// Handle delete course request
if (isset($_GET['delete_course'])) {
    $courseIdToDelete = $_GET['delete_course'];
    // Perform the database delete operation here
    $deleteQuery = "DELETE FROM course WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $courseIdToDelete);

    if ($stmt->execute()) {
        // Course deleted successfully
        header("Location: managecourse.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Query to fetch all courses from the database
$query = "SELECT * FROM course";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Manage Courses</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">

    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   
    <link rel="stylesheet" href="../style/course.css">
</head>

<body>
<?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Manage Courses</h1>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h2>Courses List</h2>
                <br>
            </div>
            <div class="col-md-7 text-right">
                <a href="addnewcourse.php" class="btn btn-success"><i class="fa fa-plus"></i> Add New Course</a>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any courses available
                if ($result && $result->num_rows > 0) {
                    // Loop through the courses and display them in a table
                    while ($row = $result->fetch_assoc()) {
                        $courseId = $row['id'];
                        $courseName = $row['name'];
                        $courseDescription = $row['desc'];
                        ?>
                        <tr>
                            <td><?php echo $courseId; ?></td>
                            <td><?php echo $courseName; ?></td>
                            <td><?php echo $courseDescription; ?></td>
                            <td>
                            <div class="btn-group" role="group">
    <a href="editcourse.php?id=<?php echo $courseId; ?>" class="btn btn-primary mr-2">Edit</a>
    <a href="?delete_course=<?php echo $courseId; ?>" class="btn btn-danger mr-2" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
    <a href="#" class="btn btn-info" data-toggle="modal" data-target="#lessonPreviewModal" data-lessonid="<?php echo $lessonId; ?>">Preview</a>
</div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    // Display a message if there are no available courses
                    echo '<tr><td colspan="4">Sorry, there are no available courses right now.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    
    <?php include('../includes/footer.php'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="../js/course.js"></script>
</body>

</html>

        