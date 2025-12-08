function togglePasswordFields() {
    const changePassword = document.getElementById('change_password');
    const passwordFields = document.getElementById('password_fields');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    if (changePassword.checked) {
        passwordFields.style.display = 'flex';
        newPassword.required = true;
        confirmPassword.required = true;
    } else {
        passwordFields.style.display = 'none';
        newPassword.required = false;
        confirmPassword.required = false;
        newPassword.value = '';
        confirmPassword.value = '';
    }
}

