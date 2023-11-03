<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
define('TITLE', 'Dashboard');
define('PAGE', 'dashboard');
include('../includes/header.php');
include('../../includes/config.php');

if(isset($_SESSION['is_admin_login'])){
  $adminEmail = $_SESSION['adminLogEmail'];
} else {
  echo "<script> location.href='../index.php'; </script>";
}

// Fetch the total number of courses
$sql = "SELECT * FROM course";
$result = $conn->query($sql);
$totalCourses = $result->num_rows;

// Fetch the total number of staff members
$sql = "SELECT * FROM staff";
$result = $conn->query($sql);
$totalStaff = $result->num_rows;

// Fetch the total number of course taken records
$sql = "SELECT * FROM coursetaken";
$result = $conn->query($sql);
$totalTaken = $result->num_rows;

try {
  // Database connection code

  // Database query
  $sql = "SELECT * FROM your_table";
  $result = $conn->query($sql);

  // Rest of your code to fetch data

} catch (Exception $e) {
  // Display database error
  echo "Database Error: " . $e->getMessage();
}
?>


<div class="col-sm-9 mt-5">
    <div class="row mx-5 text-center">
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                <div class="card-header">Courses</div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $totalCourses; ?>
                    </h4>
                    <a class="btn text-white" href="courses.php">View</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">Staff</div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $totalStaff; ?>
                    </h4>
                    <a class="btn text-white" href="students.php">View</a>
                </div>
            </div>
        </div>
        <div class="col-sm-4 mt-5">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                <div class="card-header">Course Taken</div>
                <div class="card-body">
                    <h4 class="card-title">
                        <?php echo $totalTaken; ?>
                    </h4>
                    <a class="btn text-white" href="sellreport.php">View</a>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-5 mt-5 text-center">
        <!--Table-->
        <p class="bg-dark text-white p-2">Course taken</p>
        <?php
        $sql = "SELECT * FROM coursetaken";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<table class="table">
                <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Course ID</th>
                        <th scope="col">Staff Email</th>
                        <th scope="col">enroll Date</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<th scope="row">' . $row["order_id"] . '</th>';
                echo '<td>' . $row["courseid"] . '</td>';
                echo '<td>' . $row["staffemail"] . '</td>';
                echo '<td>' . $row["enrolldate"] . '</td>';

                echo '<td><form action="" method="POST" class="d-inline"><input type="hidden" name="id" value=' . $row["co_id"] . '><button type="submit" class="btn btn-secondary" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button></form></td>';
                echo '</tr>';
            }
            echo '</tbody>
            </table>';
        } else {
            echo "0 Result";
        }

        if (isset($_REQUEST['delete'])) {
            $sql = "DELETE FROM coursetaken WHERE id = {$_REQUEST['id']}";
            if ($conn->query($sql) === TRUE) {
                echo '<meta http-equiv="refresh" content="0;URL=?deleted" />';
            } else {
                echo "Unable to Delete Data";
            }
        }
        ?>
    </div>
</div>
</div>
</div>
</div>

</div> <!-- div Row close from header -->
</div> <!-- div Container-fluid close from header -->

<?php
include('../includes/footer.php');
?>
