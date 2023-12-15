// lesson-main.js
define(['jquery'], function($) {
    // Your jQuery code goes here
    // Example: Change background color on document ready
    $(document).ready(function() {
        $('body').css('background-color', '#f0f0f0');
    });

    // Example: Handle click event
    $('.video-container').on('click', function() {
        alert('Video container clicked!');
    });
});
