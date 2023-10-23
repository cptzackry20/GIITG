new fullpage('#fullpage', {
    autoScrolling: true,
    navigation: true,
});

if (screen && screen.width > 991) {
    document.write('<script type="text/javascript" src="fullpage.js"><\/script>');
}

$(document).ready(function () {
    $('#fullpage').fullpage();
});

// New code for staff registration form
$(document).ready(function () {
    $('#staffRegForm').submit(function (event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // Retrieve form values
        const staffName = $("#staffname").val();
        const staffEmail = $("#staffemail").val();
        const staffPass = $("#staffpass").val();

        // Send the data to the PHP script using AJAX
        $.ajax({
            type: 'POST',
            url: 'insert_staff.php', // Create this PHP file
            data: {
                staffname: staffName,
                staffemail: staffEmail,
                staffpass: staffPass
            },
            success: function (response) {
                if (response.trim() === "success") {
                    // Registration was successful
                    $('#staffRegForm')[0].reset(); // Clear the form
                    $("#successMsg").html("<span class='text-success'>Registration successful. You can now login.</span>");
                } else if (response.trim() === "failed") {
                    // Registration failed
                    $("#successMsg").html("<span class='text-danger'>Registration failed. Please try again.</span>");
                }
            }
        });
    });
});

function checkStaffLogin() {
    const staffLogEmail = $("#staffLogEmail").val();
    const staffLogPass = $("#staffLogPass").val();

    $.ajax({
        url: "checkstafflogin.php",
        method: "POST",
        data: {
            checkLogEmail: "checkLogEmail",
            staffLogEmail: staffLogEmail,
            staffLogPass: staffLogPass,
        },
        success: function (data) {
            if (data == 0) {
                $("#statusLogMsg").html(
                    '<small class="alert alert-danger">Invalid Email ID or Password !</small>'
                );
            } else if (data == 1) {
                $("#statusLogMsg").html(
                    '<div class="spinner-border text-success" role="status"></div>'
                );
                setTimeout(() => {
                    window.location.href = "index.php";
                }, 1000);
            }
        },
    });
}
