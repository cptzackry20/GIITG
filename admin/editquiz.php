<?php
// Include the database configuration file and other necessary files
include '../includes/config.php';

// Handle the form submission to update a quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quiz'])) {
    $quizName = $_POST['quiz_name'];
    $quizDescription = $_POST['quiz_description'];
    $courseId = $_POST['course_id'];

    // You can add validation and sanitization here as needed

   // Update the quiz in the database
   $updateQuery = "UPDATE quiz SET name = ?, description = ?, course_id = ? WHERE id = ?";
   $stmt = $conn->prepare($updateQuery);

    // Check if the prepare statement was successful
   if ($stmt === false) {
        echo "Error: " . $conn->error;
        exit;
    }
    // Get the quiz ID from the URL
    $quizId = $_GET['id'];

    // Bind parameters
    $stmt->bind_param("ssii", $quizName, $quizDescription, $courseId, $quizId);

    // Check if the bind_param was successful
    if ($stmt->execute()) {
        // Quiz updated successfully
        header("Location: editquiz.php?id=" . $quizId);
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch the quiz details from the database
$quizId = $_GET['id'];
$selectQuery = "SELECT * FROM quiz WHERE id = ?";
$stmt = $conn->prepare($selectQuery);

if ($stmt === false) {
    echo "Error: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $quizId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $quiz = $result->fetch_assoc();
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
    <title>Edit Quiz</title>
    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/Bootstrap/js/bootstrap.bundle.min.js">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <div class="section web-header">
        <div class="header-container">
            <div class="header-content">
                <h1>Edit Quiz</h1>
            </div>
        </div>
    </div>
    <div class="container mt-10">
        <br>
        <h2>Edit Quiz</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="course_id">Course</label>
                <select class="form-control" id="course_id" name="course_id" required>
                    <option value="" disabled>Select a Course</option>
                    <?php
                    // Populate dropdown with available courses
                    while ($row = $courseResult->fetch_assoc()) {
                        $selected = ($row['id'] == $quiz['course_id']) ? 'selected' : '';
                        echo '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quiz_name">Quiz Name</label>
                <input type="text" class="form-control" id="quiz_name" name="quiz_name" value="<?= $quiz['name'] ?>" required>
            </div>
            <div class="form-group">
                <label for="quiz_description">Quiz Description</label>
                <textarea class="form-control" id="quiz_description" name="quiz_description" rows="4" required><?= $quiz['description'] ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="update_quiz">Update Quiz</button>
        </form>
        <br>
        
        <h2>Existing Questions</h2>
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Question Text</th>
            <th>Option A</th>
            <th>Option B</th>
            <th>Option C</th>
            <th>Option D</th>
            <th>Correct Option</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch existing questions based on the quiz ID
        $existingQuestionsQuery = "SELECT * FROM question WHERE quiz_id = ?";
        $stmt = $conn->prepare($existingQuestionsQuery);
        
        if ($stmt === false) {
            echo "Error: " . $conn->error;
            exit;
        }
        
        $stmt->bind_param("i", $quizId);
        
        if ($stmt->execute()) {
            $questionsResult = $stmt->get_result();
            $questionNumber = 1;
        
            if ($questionsResult->num_rows === 0) {
                echo "<tr><td colspan='8'>No existing questions.</td></tr>";
            }
        
            while ($row = $questionsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $questionNumber . "</td>";
                echo "<td class='question-text'>" . $row['question_text'] . "</td>";
                echo "<td class='option-a'>" . $row['option_a'] . "</td>";
                echo "<td class='option-b'>" . $row['option_b'] . "</td>";
                echo "<td class='option-c'>" . $row['option_c'] . "</td>";
                echo "<td class='option-d'>" . $row['option_d'] . "</td>";
                echo "<td class='correct-option'>" . $row['correct_option'] . "</td>";
                echo "<td>
                        <button class='btn btn-danger delete-question' data-question-id='" . $row['id'] . "'>Delete</button>
                        <button class='btn btn-primary edit-question'>Edit</button>
                      </td>";
                echo "</tr>";
                $questionNumber++;
            }
        }
        ?>
    </tbody>
</table>

        <br>
        <h2>Add Multiple-Choice Questions</h2>
        <form method="POST" action="">
            <div id="questions">
                <!-- Question 1 -->
                <div class="question">
                    <h4>Question 1</h4>
                    <div class="form-group">
                        <label for="question_text_1">Question Text</label>
                        <input type="text" class="form-control" id="question_text_1" name="question_text[]" required>
                    </div>
                    <div class "form-group">
                        <label for="option_a_1">Option A</label>
                        <input type="text" class="form-control" id="option_a_1" name="option_a[]" required>
                    </div>
                    <div class="form-group">
                        <label for="option_b_1">Option B</label>
                        <input type="text" class="form-control" id="option_b_1" name="option_b[]" required>
                    </div>
                    <div class="form-group">
                        <label for="option_c_1">Option C</label>
                        <input type="text" class="form-control" id="option_c_1" name="option_c[]" required>
                    </div>
                    <div class="form-group">
                        <label for="option_d_1">Option D</label>
                        <input type="text" class="form-control" id="option_d_1" name="option_d[]" required>
                    </div>
                    <div class="form-group">
                        <label for="correct_option_1">Correct Option (A, B, C, or D)</label>
                        <input type="text" class="form-control" id="correct_option_1" name="correct_option[]" required>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary" id="add-question">Add Another Question</button>
            <button type="submit" class="btn btn-primary" name="submit">Save Questions</button>
        </form>
        <br>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script>
    $(document).ready(function () {
        // Edit question
        $('.edit-question').click(function () {
            var row = $(this).closest('tr');
            var questionId = row.find('.delete-question').data('question-id');
            var questionText = row.find('.question-text').text();
            var optionA = row.find('.option-a').text();
            var optionB = row.find('.option-b').text();
            var optionC = row.find('.option-c').text();
            var optionD = row.find('.option-d').text();
            var correctOption = row.find('.correct-option').text();

            var editedQuestionText = prompt('Edit the question text:', questionText);
            var editedOptionA = prompt('Edit Option A:', optionA);
            var editedOptionB = prompt('Edit Option B:', optionB);
            var editedOptionC = prompt('Edit Option C:', optionC);
            var editedOptionD = prompt('Edit Option D:', optionD);
            var editedCorrectOption = prompt('Edit Correct Option (A, B, C, or D):', correctOption);

            if (editedQuestionText && editedOptionA && editedOptionB && editedOptionC && editedOptionD && editedCorrectOption) {
                row.find('.question-text').text(editedQuestionText);
                row.find('.option-a').text(editedOptionA);
                row.find('.option-b').text(editedOptionB);
                row.find('.option-c').text(editedOptionC);
                row.find('.option-d').text(editedOptionD);
                row.find('.correct-option').text(editedCorrectOption);

                $.ajax({
                    type: 'POST',
                    url: 'managequestion.php',
                    data: {
                        action: 'edit_question',
                        question_id: questionId,
                        question_text: editedQuestionText,
                        option_a: editedOptionA,
                        option_b: editedOptionB,
                        option_c: editedOptionC,
                        option_d: editedOptionD,
                        correct_option: editedCorrectOption
                    },
                    success: function (response) {
                        alert(response);
                    }
                });
            }
        });

      // Delete question
      $('.delete-question').click(function () {
            var row = $(this).closest('tr');
            var questionId = row.find('.delete-question').data('question-id');
            if (confirm('Are you sure you want to delete this question?')) {
                $.ajax({
                    type: 'POST',
                    url: 'managequestion.php',
                    data: {
                        action: 'delete_question',
                        question_id: questionId
                    },
                    success: function (response) {
                        alert(response);
                        // Remove the row from the table
                        row.remove();
                    }
                });
            }
        });
    });
</script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
