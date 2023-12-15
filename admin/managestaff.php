<?php
// Include the database configuration file for staff
include '../includes/config.php';

// Handle delete staff request
if (isset($_GET['delete_staff'])) {
    $staffIdToDelete = $_GET['delete_staff'];
    // Perform the database delete operation here
    $deleteQuery = "DELETE FROM staff WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $staffIdToDelete);

    if ($stmt->execute()) {
        // Staff member deleted successfully
        header("Location: managestaff.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Handle update user type request
if (isset($_POST['update_user_type'])) {
    $staffIdToUpdate = $_POST['staff_id'];
    $userType = $_POST['user_type'];

    // Perform the database update operation here
    $updateQuery = "UPDATE staff SET user_type = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $userType, $staffIdToUpdate);

    if ($stmt->execute()) {
        // User type updated successfully
        header("Location: managestaff.php"); // Redirect to refresh the page
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Query to fetch all staff members from the database, including the "status" column
$query = "SELECT staff.id, staff.code, staff.name, staff.email, staff.position, staff.dp, department.name AS department_name, staff.status, staff.user_type
          FROM staff
          LEFT JOIN department ON staff.department_id = department.id";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Manage Staff</title>
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
                <h1>Manage Staff</h1>
            </div>
        </div>
    </div>
    <br>
    <div class="col-md-7 text-right button-container">
        <a href="addnewstaff.php" class="btn btn-success"><i class="fa fa-plus"></i> Add New Staff</a>
        <a href="managedepartment.php" class="btn btn-success"><i class="fa fa-plus"></i> Manage Department</a>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h2>Staff List</h2>
                <br>
            </div>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if there are any staff members available
                    if ($result && $result->num_rows > 0) {
                        $counter = 1; // Initialize the counter
                        // Loop through the staff members and display them in a table
                        while ($row = $result->fetch_assoc()) {
                            $staffId = $row['id'];
                            $staffCode = $row['code'];
                            $staffName = $row['name'];
                            $staffEmail = $row['email'];
                            $staffPosition = $row['position'];
                            $departmentName = $row['department_name'];
                            $staffStatus = $row['status'];
                            $staffType = $row['user_type'];
                            ?>
                            <tr>
                                <td><?php echo $counter; ?></td>
                                <td><?php echo $staffCode; ?></td>
                                <td><?php echo $staffName; ?></td>
                                <td><?php echo $staffEmail; ?></td>
                                <td><?php echo $staffPosition; ?></td>
                                <td><?php echo $departmentName; ?></td>
                                <td>
                                    <?php
                                    if ($staffStatus === '1') {
                                        echo 'Active';
                                    } elseif ($staffStatus === '0') {
                                        echo 'Inactive';
                                    } else {
                                        echo 'Not Set';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="staff_id" value="<?php echo $staffId; ?>">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="user_type" id="userType1" value="1" <?php echo ($staffType === '1') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="userType1">Normal Staff</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="user_type" id="userType2" value="2" <?php echo ($staffType === '2') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="userType2">Instructor</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="user_type" id="userType3" value="3" <?php echo ($staffType === '3') ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="userType3">Admin</label>
                                        </div>
                                        <button type="submit" name="update_user_type" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="editstaff.php?id=<?php echo $staffId; ?>" class="btn btn-primary mr-2">Edit</a>
                                        <a href="?delete_staff=<?php echo $staffId; ?>"
                                            class="btn btn-danger mr-2"
                                            onclick="return confirm('Are you sure you want to delete this staff member?')">Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            $counter++; // Increment the counter
                        }
                    } else {
                        // Display a message if there are no available staff members
                        echo '<tr><td colspan="8">Sorry, there are no available staff members right now.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
            integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
            integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
            crossorigin="anonymous"></script>
    

            <script>
    function searchStaff() {
        // Get the search term from the input field
        var searchTerm = document.getElementById('searchInput').value.toLowerCase();

        // Get all rows in the table
        var rows = document.querySelectorAll('tbody tr');

        // Loop through the rows
        for (var i = 0; i < rows.length; i++) {
            // Get the name column value for each row
            var nameColumn = rows[i].getElementsByTagName('td')[2]; // Assuming name is in the third column (index 2)

            if (nameColumn) {
                // Convert the name to lowercase for case-insensitive comparison
                var name = nameColumn.innerText.toLowerCase();

                // Check if the name contains the search term
                if (name.includes(searchTerm)) {
                    // Display the row if it matches the search term
                    rows[i].style.display = '';
                } else {
                    // Hide the row if it does not match the search term
                    rows[i].style.display = 'none';
                }
            }
        }

        // Scroll to the first visible row
        var firstVisibleRow = document.querySelector('tbody tr[style=""]');
        if (firstVisibleRow) {
            firstVisibleRow.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }
</script>
    <script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
    <script src="../js/course.js"></script>
</body>

</html>
