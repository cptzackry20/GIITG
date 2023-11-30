<?php
// Include the database configuration file
include '../includes/config.php';

$code = $name = $email = $password = $position = $dpPath = $department = $status = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $code = $_POST['code'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Hash the password
    $position = $_POST['position'];

    // Set default image path
    $defaultImagePath = "https://static.thenounproject.com/png/5034901-200.png"; // Replace with the actual default image path

    // Check if the user uploaded a profile picture
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        // Handle the uploaded profile picture
        $uploadDir = "uploads/"; // Replace with your desired upload directory
        $uploadFile = $uploadDir . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $uploadFile);
        $dpPath = $uploadFile; // Set dpPath to the uploaded file path
    } else {
        // Use the default image path
        $dpPath = $defaultImagePath;
    }

    $department = $_POST['staff_department'];
    $status = $_POST['status'];

    // Insert data into the staff table
    $insertQuery = "INSERT INTO staff (code, name, email, password, position, dp, department_id, status) 
            VALUES ('$code', '$name', '$email', '$password', '$position', '$dpPath', '$department', '$status')";
    
    // Execute the SQL query using the connection from the config file
    if ($conn->query($insertQuery) === TRUE) {
        echo "Staff member added successfully!";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
// Query to fetch department names for the select option
$departmentQuery = "SELECT id, name FROM department";
$departmentResult = $conn->query($departmentQuery);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  
    <title>Add New Staff</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">
</head>
<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Add New Staff</h1>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <br>
        <h2>Add New Staff</h2>
            <form action="" method="post">
            <label for="code">Staff Code:</label>
            <input type="text" name="code" required><br>

            <label for="name">Name:</label>
            <input type="text" name="name" required><br>

            <label for="email">Email:</label>
            <input type="email" name="email" required><br>

            <label for="password">Password:</label>
            <input type="password" name="password" required><br>

            <label for="position">Position:</label>
            <input type="text" name="position" required><br>

            <input type="hidden" name="defaultdp" value="<?php echo $defaultImagePath; ?>">

            <label for="department_id">Department ID:</label>
                <select class="form-control" id="staff_department" name="staff_department">
                    <option value="">Select a department</option>
                    <?php
                    if ($departmentResult && $departmentResult->num_rows > 0) {
                        while ($departmentRow = $departmentResult->fetch_assoc()) {
                            echo '<option value="' . $departmentRow['id'] . '">' . $departmentRow['name'] . '</option>';
                        }
                    }
                    ?>
                </select>

            <label for="status">Status:</label>
            <input type="number" name="status" required><br>

            <input type="submit" value="Add Staff">
        </form>
        <br>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>
</html>