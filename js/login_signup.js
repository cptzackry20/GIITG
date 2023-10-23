$(document).ready(function () {
    // Initially show the login section and hide the signup section
    $('#loginSection').show();
    $('#signupSection').hide();

    // Toggle to the signup section
    $('#toggleSignup').click(function () {
        $('#loginSection').hide();
        $('#signupSection').show();
        $('#submitBtn').text('Signup');
    });

    // Toggle back to the login section
    $('#toggleLogin').click(function () {
        $('#loginSection').show();
        $('#signupSection').hide();
        $('#submitBtn').text('Login');
    });

    // Form submission handling
    $('#loginSignupForm').submit(function (event) {
        event.preventDefault();

        const formData = $(this).serialize();

        // Determine whether it's a login or signup action
        const isLogin = $('#submitBtn').text() === 'Login';
        const url = isLogin ? 'login.php' : 'signup.php'; // Update with your PHP scripts

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function (response) {
                // Handle the response, e.g., show success or error messages
                console.log(response);

                // If it's a successful signup, switch back to login
                if (!isLogin && response.trim() === 'success') {
                    $('#toggleLogin').click();
                }
            }
        });
    });
});
