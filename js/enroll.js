<script>
    $(document).ready(function() {
        $(".enroll-button").click(function() {
            var courseId = $(this).data("course");

            $.ajax({
                type: "POST",
                url: "enroll.php",
                data: { course_id: courseId },
                success: function(data) {
                    alert(data); // Display the response data in an alert
                    // You can add further logic here after enrolling the user
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown); // Log any errors to the console
                    alert("An error occurred while enrolling in the course.");
                }
            });
        });
    });
</script>
