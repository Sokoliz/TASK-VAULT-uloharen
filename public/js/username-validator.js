/**
 * Username Validator
 * Checks if username already exists during registration
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log("Username validator script loaded");
    
    // Find registration form with username field
    const registerForm = document.querySelector('form[action="/register"]');
    console.log("Registration form found:", !!registerForm);
    
    const usernameInput = registerForm ? registerForm.querySelector('input[name="username"]') : null;
    console.log("Username input found:", !!usernameInput);
    
    if (!registerForm || !usernameInput) {
        console.error("Form or username input not found");
        
        // Try alternative selector for debugging
        const allForms = document.querySelectorAll('form');
        console.log("Total forms on page:", allForms.length);
        allForms.forEach((form, i) => {
            console.log(`Form ${i} action:`, form.getAttribute('action'));
        });
        
        return; // Exit if form or username input not found
    }
    
    // Create username warning container if it doesn't exist
    let usernameWarning = document.getElementById('username-warning');
    if (!usernameWarning) {
        usernameWarning = document.createElement('div');
        usernameWarning.id = 'username-warning';
        usernameWarning.className = 'mb-4 d-none';
        usernameWarning.innerHTML = `
            <div class="alert alert-danger" role="alert">
                Používateľské meno už existuje. Prosím, zvoľte iné meno.
            </div>
        `;
        // Insert after username field's parent div (which is .mb-4)
        usernameInput.closest('.mb-4').after(usernameWarning);
    }
    
    // Add delay timer for username checking
    let usernameCheckTimer = null;
    
    // Track if username is valid
    let isUsernameValid = true;
    
    // Function to check username availability
    function checkUsername(username) {
        console.log("Checking username: " + username);
        
        // Clear any previous timer
        if (usernameCheckTimer) {
            clearTimeout(usernameCheckTimer);
        }
        
        // Set a small delay to prevent too many requests while typing
        usernameCheckTimer = setTimeout(function() {
            // Only check if username has at least 3 characters
            if (username.length < 3) {
                usernameWarning.classList.add('d-none');
                isUsernameValid = true;
                return;
            }
            
            // Make AJAX request to check username
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/check-username', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    console.log("XHR status: " + xhr.status);
                    console.log("XHR response: " + xhr.responseText);
                    
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            console.log("Parsed response: ", response);
                            
                            if (response.exists) {
                                // Username exists, show warning
                                usernameWarning.classList.remove('d-none');
                                isUsernameValid = false;
                                console.log("Username exists!");
                            } else {
                                // Username is available
                                usernameWarning.classList.add('d-none');
                                isUsernameValid = true;
                                console.log("Username is available");
                            }
                        } catch (e) {
                            console.error('Error parsing JSON response', e);
                            // If error in response, assume username is valid
                            usernameWarning.classList.add('d-none');
                            isUsernameValid = true;
                        }
                    } else {
                        // Server error, assume username is valid
                        console.error('Server error: ' + xhr.status);
                        usernameWarning.classList.add('d-none');
                        isUsernameValid = true;
                    }
                }
            };
            
            // Handle network errors
            xhr.onerror = function() {
                console.error('Network error checking username');
                // If network error, assume username is valid
                usernameWarning.classList.add('d-none');
                isUsernameValid = true;
            };
            
            // Send the request
            xhr.send('username=' + encodeURIComponent(username));
            console.log("AJAX request sent for username: " + username);
            
        }, 500); // 500ms delay to reduce requests while typing
    }
    
    // Add input event listener to username field
    usernameInput.addEventListener('input', function() {
        checkUsername(this.value.trim());
    });
    
    // Add blur event listener to force check on field blur
    usernameInput.addEventListener('blur', function() {
        // Clear any pending check
        if (usernameCheckTimer) {
            clearTimeout(usernameCheckTimer);
        }
        // Check immediately
        checkUsername(this.value.trim());
    });
    
    // Add form submit validation
    registerForm.addEventListener('submit', function(e) {
        // Check username one last time before submit
        const username = usernameInput.value.trim();
        
        // If username is empty, let form validation handle it
        if (username === '') {
            return;
        }
        
        // Prevent form submission if username is not valid
        if (!isUsernameValid) {
            e.preventDefault();
            usernameWarning.classList.remove('d-none');
            usernameInput.focus();
            console.log("Form submission prevented due to invalid username");
        }
    });
    
    console.log("Username validator initialized");
});
