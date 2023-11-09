<?php
// Include the database configuration file for staff
include '../includes/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle change status request
if (isset($_GET['change_status'])) {
    $staffIdToChange = $_GET['change_status'];
    $newStatus = $_GET['new_status'];

    // Perform the database update operation here to change the status
    $updateQuery = "UPDATE staff SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $newStatus, $staffIdToChange);

    if ($stmt->execute()) {
        // Status changed successfully
        // You may add a success message here
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Query to fetch all staff members for instructors from the database, including the "status" column
$query = "SELECT staff.id, staff.code, staff.name, staff.email, staff.position, department.name AS department_name, staff.status
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
                <h1>Manage Staff for Instructors</h1>
            </div>
        </div>
    </div>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h2>Staff List</h2>
                <br>
            </div>
        </div>
        <table class="table table-bordered" id="staffTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        $staffId = $row['id'];
                        $staffCode = $row['code'];
                        $staffName = $row['name'];
                        $staffEmail = $row['email'];
                        $staffPosition = $row['position'];
                        $departmentName = $row['department_name'];
                        $staffStatus = $row['status'];

                        // Display "Not Set" if status is null
                        $statusText = ($staffStatus === null) ? 'Not Set' : ($staffStatus == 1 ? 'Active' : 'Inactive');
?>
                        <tr>
                            <td><?php echo $count++; ?></td>
                            <td><?php echo $staffCode; ?></td>
                            <td><?php echo $staffName; ?></td>
                            <td><?php echo $staffEmail; ?></td>
                            <td><?php echo $staffPosition; ?></td>
                            <td><?php echo $departmentName; ?></td>
                            <td>
                                <form action="" method="get">
                                    <input type="hidden" name="change_status" value="<?php echo $staffId; ?>">
                                    <select name="new_status" onchange="this.form.submit()">
                                    <option value="null" <?php echo ($staffStatus === null) ? 'selected' : ''; ?>>Not Set</option>

                                        <option value="1" <?php echo ($staffStatus == 1) ? 'selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo ($staffStatus == 0) ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo '<tr><td colspan="7">Sorry, there are no available staff members right now.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php include('../includes/footer.php'); ?>
    <script>
        $(document).ready(function() {
            $('#staffTable').DataTable({
                "paging": true,  // Enable pagination
                "searching": true,  // Enable search
                "order": [[1, 'asc']],  // Default sorting by the second column (code)
                "columnDefs": [
                    { "orderable": false, "targets": [6] }  // Disable sorting for the "Status" column
                ]
            });
        });
    </script>

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
    <script src="../js/course.js"></script>
</body>
</html>
