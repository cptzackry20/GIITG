<?php
include '../includes/config.php';

$quizUpdateStatus = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $quizId = isset($_GET['id']) ? $_GET['id'] : null;

    if ($quizId !== null && !empty($_POST['question_text']) && is_array($_POST['question_text'])) {
        foreach ($_POST['question_text'] as $index => $questionText) {
            $optionA = $_POST['option_a'][$index];
            $optionB = $_POST['option_b'][$index];
            $optionC = $_POST['option_c'][$index];
            $optionD = $_POST['option_d'][$index];
            $correctOption = $_POST['correct_option'][$index];

            $insertQuestionQuery = "INSERT INTO question (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuestionQuery);

            if ($stmt === false) {
                echo "Error: " . $conn->error;
                exit;
            }

            $stmt->bind_param("issssss", $quizId, $questionText, $optionA, $optionB, $optionC, $optionD, $correctOption);

            if ($stmt->execute()) {
                $quizUpdateStatus = 'Questions added successfully';
            }
        }
    }
}

$quizId = isset($_GET['id']) ? $_GET['id'] : null;

if ($quizId !== null) {
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
}

$courseQuery = "SELECT id, name FROM course";
$courseResult = $conn->query($courseQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Edit Quiz</title>
    <link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">

    <link rel="stylesheet" href="../style/Bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="../style/course.css">
    <link rel="stylesheet" href="../style/style.css">
    <style>
        .navbar {
            background-color: #333;
        }
        .navbar-dark .navbar-nav .nav-link {
            color: #fff;
        }
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
                $quizId = isset($_GET['id']) ? $_GET['id'] : null;

                if ($quizId !== null) {
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
                }
                ?>
            </tbody>
        </table>
        <br>
        <h2>Add Multiple-Choice Questions</h2>
        <div id="mcq-form">
            <form method="POST" action="">
            <div id="questions">
                <!-- Question 1 -->
                <div class="question">
                    <h4>Question</h4>
                    <div class="form-group">
                        <label for="question_text_1">Question Text</label>
                        <input type="text" class="form-control" id="question_text_1" name="question_text[]" required>
                    </div>
                    <div class="form-group">
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
            <button type="submit" class="btn btn-primary" name="submit">Save Questions</button>
        </form>
        <br>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>

    <script>
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
    var questionId = $(this).data('question-id');
    var quizId = <?php echo $quizId; ?>; // Get the quiz ID from PHP

    if (confirm('Are you sure you want to delete this question?')) {
        $.ajax({
            type: 'POST',
            url: 'managequestion.php', // Make sure the URL is correct
            data: {
                action: 'delete_question',
                question_id: questionId,
                quiz_id: quizId // Pass the quiz ID to 'managequestion.php'
            },
            success: function (response) {
                alert(response); // Display the response from the server
                // If the response indicates success, you can remove the row from the table
                if (response === "Question deleted successfully") {
                    row.remove();
                }
            }
        });
    }
});


// Add more questions
var questionNumber = 1;
$('#add-question').click(function () {
    questionNumber++;
    var newQuestion = `
        <div class="question">
            <h4>Question</h4>
            <div class="form-group">
                <label for="question_text_${questionNumber}">Question Text</label>
                <input type="text" class="form-control" id="question_text_${questionNumber}" name="question_text[]" required>
            </div>
            <div class="form-group">
                <label for="option_a_${questionNumber}">Option A</label>
                <input type="text" class="form-control" id="option_a_${questionNumber}" name="option_a[]" required>
            </div>
            <div class="form-group">
                <label for="option_b_${questionNumber}">Option B</label>
                <input type="text" class="form-control" id="option_b_${questionNumber}" name="option_b[]" required>
            </div>
            <div class="form-group">
                <label for="option_c_${questionNumber}">Option C</label>
                <input type="text" class="form-control" id="option_c_${questionNumber}" name="option_c[]" required>
            </div>
            <div class="form-group">
                <label for="option_d_${questionNumber}">Option D</label>
                <input type="text" class="form-control" id="option_d_${questionNumber}" name="option_d[]" required>
            </div>
            <div class="form-group">
                <label for="correct_option_${questionNumber}">Correct Option (A, B, C, or D)</label>
                <input type="text" class="form-control" id="correct_option_${questionNumber}" name="correct_option[]" required>
            </div>
        </div>
    `;
    $('#questions').append(newQuestion);
});
</script>

<script src="https://kit.fontawesome.com/9fb210ee5d.js" crossorigin="anonymous"></script>
</body>
</html>