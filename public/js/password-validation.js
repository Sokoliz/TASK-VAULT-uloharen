/**
 * Password validation for registration form
 */

document.addEventListener('DOMContentLoaded', function() {
    // Add event listener for form submission
    const form = document.querySelector('form[name="signup"]');
    if (form) {
        form.addEventListener('submit', function(event) {
            if (!validatePasswords()) {
                event.preventDefault();
            }
        });
    }
    
    // Add event listener for real-time validation
    const confirmPasswordField = document.getElementById('password2');
    if (confirmPasswordField) {
        confirmPasswordField.addEventListener('input', checkPasswordMatch);
    }
});

/**
 * Validates if both passwords match
 * @returns {boolean} True if passwords match, false otherwise
 */
function validatePasswords() {
    const password = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;
    const warningElement = document.getElementById('password-warning');
    
    if (password !== password2) {
        warningElement.classList.remove('d-none');
        return false;
    } else {
        warningElement.classList.add('d-none');
        return true;
    }
}

/**
 * Checks if passwords match as user types
 */
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const password2 = document.getElementById('password2').value;
    const warningElement = document.getElementById('password-warning');
    
    if (password2.length > 0 && password !== password2) {
        warningElement.classList.remove('d-none');
    } else {
        warningElement.classList.add('d-none');
    }
} 