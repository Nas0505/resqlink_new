$(document).ready(function() {
    $('#signup').on('submit', function(e) {
        const password = $('.password-field').val().trim();
        const confirm = $('.confirm-password-field').val().trim();
        const errorBox = $('#errorMsg');

        errorBox.text('');
        $('.confirm-password-field').css('border-color', '');

        if (password !== confirm) {
            $('.confirm-password-field').css('border-color', 'red');
            errorBox.text('Passwords do not match.');
            e.preventDefault(); // Prevent form submission
        }
    });
});