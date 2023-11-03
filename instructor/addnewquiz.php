<?php
// Include the database configuration file
include '../includes/config.php';

// Handle the form submission to add a new quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quizName = $_POST['quiz_name'];
    $quizDescription = $_POST['quiz_description'];
    $courseId = $_POST['course_id'];

    // You can add validation and sanitization here as needed

    // Insert the new quiz into the database
    $insertQuery = "INSERT INTO quiz (name, description, course_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);

    // Check if the prepare statement was successful
    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    // Bind parameters
    $stmt->bind_param("ssi", $quizName, $quizDescription, $courseId);

    // Check if the bind_param was successful
    if ($stmt->execute()) {
        // Quiz added successfully
        header("Location: editquiz.php?id=" . $stmt->insert_id); // Redirect to edit quiz page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch a list of courses for the dropdown
$courseQuery = "SELECT id, name FROM course";
$courseResult = $conn->query($courseQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Add New Quiz</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">
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
<?php include '../includes/instructornavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Add New Quiz</h1>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <br>
        <h2>Add New Quiz</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="course_id">Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="" disabled selected>Select a Course</option>
                    <?php
                    // Populate dropdown with available courses
                    if ($courseResult->num_rows > 0) {
                        while ($row = $courseResult->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No courses available</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quiz_name">Quiz Name</label>
                <input type="text" class="form-control" id="quiz_name" name="quiz_name" required>
            </div>
            <div class="form-group">
                <label for="quiz_description">Quiz Description</label>
                <textarea class="form-control" id="quiz_description" name="quiz_description" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Quiz</button>
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