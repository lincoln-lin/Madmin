$('#resetPasswordModal').on('shown.bs.modal', function () {
    $('#login-email').val($('#loginform-login').val())
})