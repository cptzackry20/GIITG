<?php
session_start();
error_reporting(0);
include('../includes/config.php');

// Initialize variables to store the counts
$totalCourses = 0;
$totalStaff = 0;
$totalAdmin = 0;
$totalCourseTaken = 0;
$totalLessons = 0;
$totalFeedback = 0;

// Fetch data from the database
try {
    // Total Courses
    $sql = "SELECT COUNT(*) as totalCourses FROM course";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $totalCourses = $result->fetch_assoc()['totalCourses'];
    }

    // Total Staff
    $sql = "SELECT COUNT(*) as totalStaff FROM staff";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $totalStaff = $result->fetch_assoc()['totalStaff'];
    }

    // Total Admin
    $sql = "SELECT COUNT(*) as totalAdmin FROM admin";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $totalAdmin = $result->fetch_assoc()['totalAdmin'];
    }

    // Total Course Taken
    $sql = "SELECT COUNT(*) as totalCourseTaken FROM coursetaken";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $totalCourseTaken = $result->fetch_assoc()['totalCourseTaken'];
    }

    // Total Lessons
    $sql = "SELECT COUNT(*) as totalLessons FROM lesson";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $totalLessons = $result->fetch_assoc()['totalLessons'];
    }

    // Total Feedback
    $sql = "SELECT COUNT(*) as totalFeedback FROM feedback";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $totalFeedback = $result->fetch_assoc()['totalFeedback'];
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<link rel="shortcut icon" href="https://i.ibb.co/F8pCvb0/logo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin</title>
    <meta name="author" content="name">
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/> <!--Replace with your tailwind.css once created-->
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet"> <!--Totally optional :) -->
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js" integrity="sha256-xKeoJ50pzbUGkpQxDYHD7o7hxe0LaOGeguUidbq6vis=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-gray-800 font-sans leading-normal tracking-normal mt-12">

<header>
<?php include '../includes/adminnavbar.php'; ?>
        <section>
            <div id="main" class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5">

                <div class="bg-gray-800 pt-3">
                    <div class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white">
                        <h1 class="font-bold pl-2">Analytics</h1>
                    </div>
                </div>

                <div class="flex flex-wrap">
                     <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card for Total Courses-->
                        <div class="bg-gradient-to-b from-green-200 to-green-100 border-b-4 border-green-600 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-green-600"><i class="fa fa-graduation-cap fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Total Course</h2>
                                    <p class="font-bold text-3xl"><?php echo $totalCourses; ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card for Total Courses-->
                    </div>
                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card for Total Staff-->
                        <div class="bg-gradient-to-b from-pink-200 to-pink-100 border-b-4 border-pink-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-pink-600"><i class="fas fa-users fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Total Staff</h2>
                                    <p class="font-bold text-3xl"><?php echo $totalStaff; ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card for Total Staff-->
                    </div>
                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card for Total Admin-->
                        <div class="bg-gradient-to-b from-yellow-200 to-yellow-100 border-b-4 border-yellow-600 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-yellow-600"><i class="fas fa-users fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Total Admin</h2>
                                    <p class="font-bold text-3xl"><?php echo $totalAdmin; ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card for Total Admin-->
                    </div>
                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card for Course Taken-->
                        <div class="bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-blue-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-blue-600"><i class="fas fa-server fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Course Taken</h2>
                                    <p class="font-bold text-3xl"><?php echo $totalCourseTaken; ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card for Course Taken-->
                    </div>
                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card for Total Lessons-->
                        <div class="bg-gradient-to-b from-indigo-200 to-indigo-100 border-b-4 border-indigo-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-indigo-600"><i class="fas fa-file fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Lesson</h2>
                                    <p class="font-bold text-3xl"><?php echo $totalLessons; ?> </p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card for Total Lessons-->
                    </div>
                    <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card for Total Feedback-->
                        <div class="bg-gradient-to-b from-red-200 to-red-100 border-b-4 border-red-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-red-600"><i class="fas fa-comment fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h2 class="font-bold uppercase text-gray-600">Feedback</h2>
                                    <p class="font-bold text-3xl"><?php echo $totalFeedback; ?></p>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card for Total Feedback-->
                    </div>
                </div>


                <div class="flex flex-row flex-wrap flex-grow mt-2">

                <div class="w-full md:w-1/2 xl:w-1/3 p-6">
                    <!--Graph Card-->
                    <div class="bg-white border-transparent rounded-lg shadow-xl">
                        <div class="bg-gradient-to-b from-gray-300 to-gray-100 uppercase text-gray-800 border-b-2 border-gray-300 rounded-tl-lg rounded-tr-lg p-2">
                            <h class="font-bold uppercase text-gray-600">Graph</h>
                        </div>
                        <div class="p-5">
                            <canvas id="chartjs-7" class="chartjs" width="undefined" height="undefined"></canvas>
                            <script>
                                new Chart(document.getElementById("chartjs-7"), {
                                    "type": "bar",
                                    "data": {
                                        "labels": ["January", "February", "March", "April"],
                                        "datasets": [{
                                            "label": "Page Impressions",
                                            "data": [10, 20, 30, 40],
                                            "borderColor": "rgb(255, 99, 132)",
                                            "backgroundColor": "rgba(255, 99, 132, 0.2)"
                                        }, {
                                            "label": "Adsense Clicks",
                                            "data": [5, 15, 10, 30],
                                            "type": "line",
                                            "fill": false,
                                            "borderColor": "rgb(54, 162, 235)"
                                        }]
                                    },
                                    "options": {
                                        "scales": {
                                            "yAxes": [{
                                                "ticks": {
                                                    "beginAtZero": true
                                                }
                                            }]
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                    <!--/Graph Card-->
                </div>



                </div>
            </div>
        </section>
    </div>
</main>




<script>
    /*Toggle dropdown list*/
    function toggleDD(myDropMenu) {
        document.getElementById(myDropMenu).classList.toggle("invisible");
    }
    /*Filter dropdown options*/
    function filterDD(myDropMenu, myDropMenuSearch) {
        var input, filter, ul, li, a, i;
        input = document.getElementById(myDropMenuSearch);
        filter = input.value.toUpperCase();
        div = document.getElementById(myDropMenu);
        a = div.getElementsByTagName("a");
        for (i = 0; i < a.length; i++) {
            if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
                a[i].style.display = "";
            } else {
                a[i].style.display = "none";
            }
        }
    }
    // Close the dropdown menu if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.drop-button') && !event.target.matches('.drop-search')) {
            var dropdowns = document.getElementsByClassName("dropdownlist");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (!openDropdown.classList.contains('invisible')) {
                    openDropdown.classList.add('invisible');
                }
            }
        }
    }
</script>


</body>

</html>
