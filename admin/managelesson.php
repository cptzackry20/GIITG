<?php
// Include the database configuration file
include '../includes/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle delete lesson request
if (isset($_GET['delete_lesson'])) {
    $lessonIdToDelete = $_GET['delete_lesson'];
    // Perform the database delete operation here
    $deleteQuery = "DELETE FROM lesson WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $lessonIdToDelete);

    if ($stmt->execute()) {
        // Lesson deleted successfully
        header("Location: managelesson.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Query to fetch all lessons from the database
$query = "SELECT * FROM lesson";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Manage Lessons</title>
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullPage.js/3.0.9/fullpage.min.css"
        integrity="sha512-8M8By+q+SldLyFJbybaHoAPD6g07xyOcscIOQEypDzBS+sTde5d6mlK2ANIZPnSyxZUqJfCNuaIvjBUi8/RS0w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../style/course.css">
</head>

<body>
<?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Manage Lessons</h1>
            </div>
        </div>
    </div>

    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-right">
                <a href="addnewlesson.php" class="btn btn-success"><i class="fa fa-plus"></i> Add New Lesson</a>
            </div>
        </div>
        <br>
        <div class="table-responsive">
            <table class="table table-bordered table-fixed" style="width: 90%;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Lesson Name</th>
                        <th>Course Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any lessons available
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $lessonId = $row['id'];
                            $lessonName = $row['name'];
                            $courseName = $row['course_name'];
                            $lessonDescription = htmlspecialchars_decode($row['desc']);
                            $lessonLink = $row['link'];
                            ?>
                            <tr>
                                <td><?php echo $lessonId; ?></td>
                                <td><?php echo $lessonName; ?></td>
                                <td><?php echo $courseName; ?></td>
                                <td>
                                    
                                        <a href="editlesson.php?id=<?php echo $lessonId; ?>" class="btn btn-primary">Edit</a>
                                        <a href="?delete_lesson=<?php echo $lessonId; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this lesson?')">Delete</a>
                                        <a href="previewlessondetails.php?id=<?php echo $lessonId; ?>" class="btn btn-info">Preview</a> <!-- Redirect to the preview page -->
                                    
                                </td>
                            </tr>
                            <?php
                        }
                    } else { 
                        // Display a message if there are no available lessons
                        echo '<tr><td colspan="4">Sorry, there are no available lessons right now.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>

    <!-- Remove the modal and AJAX script -->

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
    <script src="../js/script.js"></script>
</body>

</html>
