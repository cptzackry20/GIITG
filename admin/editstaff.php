<?php
// Include the database configuration file
include '../includes/config.php';

// Initialize variables
$staffId = 0;
$staffName = "";
$staffEmail = "";
$departmentName = "";

// Check if the staff ID is provided in the query string
if (isset($_GET['id'])) {
    $staffId = $_GET['id'];

    // Retrieve staff details from the database
    $selectQuery = "SELECT s.*, d.name AS department_name 
                    FROM staff s 
                    LEFT JOIN department d ON s.department_id = d.id
                    WHERE s.id = ?";
    $stmt = $conn->prepare($selectQuery);
    $stmt->bind_param("i", $staffId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $staffName = $row['name'];
            $staffEmail = $row['email'];
            $departmentName = $row['department_name'];
        } else {
            echo "Staff member not found.";
            exit;
        }
    } else {
        echo "Error: " . $conn->error;
        exit;
    }
}

// Query to fetch department names for the dropdown list
$departmentQuery = "SELECT id, name FROM department";
$departmentResult = $conn->query($departmentQuery);
$departmentOptions = array();

if ($departmentResult && $departmentResult->num_rows > 0) {
    while ($departmentRow = $departmentResult->fetch_assoc()) {
        $departmentOptions[$departmentRow['id']] = $departmentRow['name'];
    }
}

// Handle the form submission to update staff information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staffName = $_POST['staff_name'];
    $staffEmail = $_POST['staff_email'];
    $departmentId = $_POST['department_id'];

    // Update staff information in the database
    $updateQuery = "UPDATE staff SET name = ?, email = ?, department_id = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);

    // Check if the prepare statement was successful
    if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }

    // Bind parameters
    $stmt->bind_param("ssii", $staffName, $staffEmail, $departmentId, $staffId);

    // Check if the bind_param was successful
    if ($stmt->execute()) {
        // Staff member updated successfully
        header("Location: managestaff.php"); // Redirect to the manage staff page
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
    <title>Edit Staff Member</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">

    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../style/staff.css">
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
        
        /* Center align the content */
        .content {
            text-align: center;
            margin-top: 20px;
        }
        
        /* Improved form styling */
        .form-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include '../includes/adminnavbar.php'; ?>

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Edit Staff</h1>
            </div>
        </div>
    </div>

    <div class="container mt-5 content">
        <div class="row">
            <div class="col-md-6 offset-md-3 form-container">
                <h2>Edit Staff</h2>
                <form method="POST" action="">
                    <input type="hidden" name="staff_id" value="<?php echo $staffId; ?>">
                    <div class="form-group">
                        <label for="staff_name">Name</label>
                        <input type="text" class="form-control" id="staff_name" name="staff_name" value="<?php echo $staffName; ?>">
                    </div>
                    <div class="form-group">
                        <label for="staff_email">Email</label>
                        <input type="text" class="form-control" id="staff_email" name="staff_email" value="<?php echo $staffEmail; ?>">
                    </div>
                    <div class="form-group">
                        <label for="department_id">Department</label>
                        <select class="form-control" id="department_id" name="department_id" required>
                            <?php
                            foreach ($departmentOptions as $optionId => $optionName) {
                                // Check if this option matches the current department ID
                                $selected = ($optionId == $departmentId) ? 'selected' : '';
                                echo "<option value=\"$optionId\" $selected>$optionName</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Staff</button>
                </form>
            </div>
        </div>
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
