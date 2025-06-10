/**
 * Form Validation Scripts
 * Contains general form validation functions used across the application
 */

// Calendar form validation
function validaForm(form) {
    console.log("validaForm called");
    
    // If delete is checked, allow submission
    if(form.delete && form.delete.checked) {
        console.log("Delete is checked, allowing form submission");
        return true;
    }
    
    // Validate date ranges
    if(form.start_date && form.end_date && form.start_date.value > form.end_date.value) {
        alert("The start date has to be before the end date.");
        return false;
    }
    
    return true;
}

// Password validation for registration form
document.addEventListener("DOMContentLoaded", function() {
    const passwordField = document.getElementById("password");
    const confirmPasswordField = document.getElementById("password2");
    const passwordWarning = document.getElementById("password-warning");
    
    if (passwordField && confirmPasswordField) {
        // Function to check password match
        function checkPasswordMatch() {
            if (passwordField.value !== confirmPasswordField.value) {
                passwordWarning.classList.remove("d-none");
                return false;
            } else {
                passwordWarning.classList.add("d-none");
                return true;
            }
        }
        
        // Add event listeners
        passwordField.addEventListener("keyup", checkPasswordMatch);
        confirmPasswordField.addEventListener("keyup", checkPasswordMatch);
        
        // Validate form on submit
        const form = confirmPasswordField.closest("form");
        if (form) {
            form.addEventListener("submit", function(e) {
                if (!checkPasswordMatch()) {
                    e.preventDefault();
                }
            });
        }
    }
    
    // Initialize project form validation
    initializeProjectFormValidation();
    
    // Initialize task form validation
    initializeTaskFormValidation();
});

// Project form validation
function validateProjectForm(form) {
    const requiredFields = [
        "project_name", 
        "start_date", 
        "end_date"
    ];
    
    let isValid = true;
    let errors = [];
    
    requiredFields.forEach(function(field) {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input || !input.value.trim()) {
            isValid = false;
            if (input) {
                input.classList.add("is-invalid");
                
                // Add field-specific error message
                if (field === "project_name") {
                    errors.push("Project name is required");
                } else if (field === "start_date") {
                    errors.push("Start date is required");
                } else if (field === "end_date") {
                    errors.push("End date is required");
                }
            }
        } else if (input) {
            input.classList.remove("is-invalid");
        }
    });
    
    // Date validation
    const startDate = form.querySelector('[name="start_date"]');
    const endDate = form.querySelector('[name="end_date"]');
    
    if (startDate && endDate && startDate.value && endDate.value) {
        if (new Date(startDate.value) > new Date(endDate.value)) {
            startDate.classList.add("is-invalid");
            endDate.classList.add("is-invalid");
            errors.push("Start date cannot be after end date");
            isValid = false;
        }
    }
    
    // Display errors if any
    const errorContainer = form.querySelector(".validation-errors");
    if (errorContainer) {
        if (errors.length > 0) {
            errorContainer.innerHTML = errors.map(error => `<div>${error}</div>`).join("");
            errorContainer.style.display = "block";
        } else {
            errorContainer.style.display = "none";
        }
    }
    
    return isValid;
}

// Task form validation
function validateTaskForm(form) {
    const requiredFields = [
        "task_name",
        "deadline"
    ];
    
    let isValid = true;
    let errors = [];
    
    requiredFields.forEach(function(field) {
        const input = form.querySelector(`[name="${field}"]`);
        if (!input || !input.value.trim()) {
            isValid = false;
            if (input) {
                input.classList.add("is-invalid");
                
                // Add field-specific error message
                if (field === "task_name") {
                    errors.push("Task name is required");
                } else if (field === "deadline") {
                    errors.push("Deadline is required");
                }
            }
        } else if (input) {
            input.classList.remove("is-invalid");
        }
    });
    
    // Validate priority
    const priorityField = form.querySelector('[name="task_colour"]');
    if (priorityField && (!priorityField.value || priorityField.value.trim() === '')) {
        priorityField.classList.add("is-invalid");
        errors.push("Please select a priority");
        isValid = false;
    } else if (priorityField) {
        priorityField.classList.remove("is-invalid");
    }
    
    // Display errors if any
    const errorContainer = form.querySelector(".validation-errors");
    if (errorContainer) {
        if (errors.length > 0) {
            errorContainer.innerHTML = errors.map(error => `<div>${error}</div>`).join("");
            errorContainer.style.display = "block";
        } else {
            errorContainer.style.display = "none";
        }
    }
    
    return isValid;
}

// Initialize project form validation
function initializeProjectFormValidation() {
    // Get new project form
    const projectForm = document.querySelector("#new-project-modal form");
    if (projectForm && !projectForm.hasAttribute('data-validation-initialized')) {
        // Add validation error container if it doesn't exist
        if (!projectForm.querySelector(".validation-errors")) {
            const errorDiv = document.createElement("div");
            errorDiv.className = "alert alert-danger validation-errors";
            errorDiv.style.display = "none";
            
            // Insert at the beginning of the form
            const modalBody = projectForm.querySelector(".modal-body");
            if (modalBody) {
                const firstChild = modalBody.firstChild;
                modalBody.insertBefore(errorDiv, firstChild);
            }
        }
        
        // Mark form as initialized
        projectForm.setAttribute('data-validation-initialized', 'true');
        
        // We'll let modal-handler.js handle the submit event
        console.log("Project form validation initialized");
    }
}

// Initialize task form validation
function initializeTaskFormValidation() {
    // Get new task form
    const taskForm = document.querySelector("#new-task-modal form");
    if (taskForm && !taskForm.hasAttribute('data-validation-initialized')) {
        // Add validation error container if it doesn't exist
        if (!taskForm.querySelector(".validation-errors")) {
            const errorDiv = document.createElement("div");
            errorDiv.className = "alert alert-danger validation-errors";
            errorDiv.style.display = "none";
            
            // Insert at the beginning of the form
            const modalBody = taskForm.querySelector(".modal-body");
            if (modalBody) {
                const firstChild = modalBody.firstChild;
                modalBody.insertBefore(errorDiv, firstChild);
            }
        }
        
        // Mark form as initialized
        taskForm.setAttribute('data-validation-initialized', 'true');
        
        // We'll let modal-handler.js handle the submit event
        console.log("Task form validation initialized");
    }
} 