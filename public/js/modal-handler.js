/**
 * Modal Handler Scripts
 * Contains functions for modal window handling, AJAX submissions, and responses
 */

/**
 * Initialize modals and forms when the document is ready
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM content loaded, initializing forms and modals");
    
    // Find all modal forms with data-ajax attribute
    const ajaxForms = document.querySelectorAll('form[data-ajax="true"]');
    
    ajaxForms.forEach(function(form) {
        setupAjaxForm(form);
    });
    
    // Add event listeners to modal triggers
    setupModalTriggers();
    
    // Setup events for calendar color dropdowns
    setupColorSelectEvents();
    
    // Setup form validation
    setupFormValidation();
    
    // Setup event-modal handling if we're on the calendar page
    if (document.querySelector('#calendar')) {
        console.log("Calendar detected, initializing event modal handling");
        initializeEventModalHandling();
    }
    
    // Log all forms on the page for debugging
    console.log("Forms found on page:", document.querySelectorAll('form').length);
    document.querySelectorAll('form').forEach((form, index) => {
        console.log(`Form ${index} action:`, form.getAttribute('action'));
    });
    
    // Specifically check for new task form
    const newTaskForm = document.querySelector('#new-task-modal form');
    if (newTaskForm) {
        console.log("New task form found", newTaskForm);
        console.log("New task form action:", newTaskForm.getAttribute('action'));
    } else {
        console.log("New task form not found");
    }
});

/**
 * Setup AJAX form submission
 */
function setupAjaxForm(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = this.action;
        
        fetch(url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close the modal
                const modal = this.closest('.modal');
                if (modal) {
                    $(modal).modal('hide');
                }
                
                // Refresh the page if needed
                if (data.refresh) {
                    window.location.reload();
                }
            } else {
                // Display error message
                alert(data.message || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    });
}

/**
 * Setup modal triggers
 */
function setupModalTriggers() {
    const modalTriggers = document.querySelectorAll('[data-toggle="modal"]');
    
    modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            $(target).modal('show');
        });
    });
}

/**
 * Setup color select events for calendar events
 */
function setupColorSelectEvents() {
    // Find all color select dropdowns
    const colorSelects = document.querySelectorAll('select[name="color"]');
    
    colorSelects.forEach(function(select) {
        // Set initial color for the select element
        updateSelectColor(select);
        
        // Add change event listener
        select.addEventListener('change', function() {
            updateSelectColor(this);
        });
    });
    
    // Project color selects
    const projectColorSelects = document.querySelectorAll('select[name="project_colour"]');
    
    projectColorSelects.forEach(function(select) {
        // Set initial color for the select element
        updateSelectColor(select);
        
        // Add change event listener
        select.addEventListener('change', function() {
            updateSelectColor(this);
        });
    });
    
    // Task color selects
    const taskColorSelects = document.querySelectorAll('select[name="task_colour"]');
    
    taskColorSelects.forEach(function(select) {
        // Set initial color for the select element
        updateSelectColor(select);
        
        // Add change event listener
        select.addEventListener('change', function() {
            updateSelectColor(this);
        });
    });
}

/**
 * Update select element background color based on selected value
 */
function updateSelectColor(select) {
    const value = select.value;
    if (value) {
        // Always keep background white but change text color
        select.style.backgroundColor = '#ffffff';
        select.style.color = value;
        select.style.fontWeight = 'bold';
    } else {
        select.style.backgroundColor = '#ffffff';
        select.style.color = '';
        select.style.fontWeight = 'normal';
    }
}

/**
 * Get contrasting text color (black or white) based on background color
 */
function getContrastColor(hexColor) {
    // Remove the hash if it exists
    hexColor = hexColor.replace('#', '');
    
    // Parse the color
    const r = parseInt(hexColor.substr(0, 2), 16);
    const g = parseInt(hexColor.substr(2, 2), 16);
    const b = parseInt(hexColor.substr(4, 2), 16);
    
    // Calculate luminance
    const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
    
    // Return black for light colors, white for dark colors
    return luminance > 0.5 ? '#000000' : '#ffffff';
}

/**
 * Setup validation for project and task forms
 */
function setupFormValidation() {
    // Setup validation for new project form
    const newProjectForm = document.querySelector('#new-project-modal form');
    if (newProjectForm) {
        newProjectForm.addEventListener('submit', function(e) {
            // Always prevent the default form submission first
            e.preventDefault();
            
            // Reset previous error states
            const errorContainer = this.querySelector(".validation-errors");
            if (errorContainer) {
                errorContainer.style.display = "none";
                errorContainer.innerHTML = "";
            }
            
            // Remove previous error styling
            this.querySelectorAll(".is-invalid").forEach(field => {
                field.classList.remove("is-invalid");
            });
            
            // Validate the form
            if (typeof validateProjectForm === 'function' && validateProjectForm(this)) {
                // If validation passes, submit the form
                this.submit();
            }
        });
    }
    
    // Setup validation for new task form
    const newTaskForm = document.querySelector('#new-task-modal form');
    if (newTaskForm) {
        newTaskForm.addEventListener('submit', function(e) {
            // Always prevent the default form submission first
            e.preventDefault();
            
            // Reset previous error states
            const errorContainer = this.querySelector(".validation-errors");
            if (errorContainer) {
                errorContainer.style.display = "none";
                errorContainer.innerHTML = "";
            }
            
            // Remove previous error styling
            this.querySelectorAll(".is-invalid").forEach(field => {
                field.classList.remove("is-invalid");
            });
            
            // Check required fields directly in this handler
            let errors = [];
            let hasErrors = false;
            
            // Validate task name
            const nameField = this.querySelector("[name='task_name']");
            if (!nameField.value.trim()) {
                nameField.classList.add("is-invalid");
                errors.push("Task name is required");
                hasErrors = true;
            }
            
            // Validate priority
            const priorityField = this.querySelector("[name='task_colour']");
            if (!priorityField.value || priorityField.value.trim() === '') {
                priorityField.classList.add("is-invalid");
                errors.push("Please select a priority");
                hasErrors = true;
            }
            
            // Validate deadline
            const deadlineField = this.querySelector("[name='deadline']");
            if (!deadlineField.value) {
                deadlineField.classList.add("is-invalid");
                errors.push("Deadline is required");
                hasErrors = true;
            }
            
            // Display errors or submit the form
            if (hasErrors) {
                if (errorContainer) {
                    errorContainer.innerHTML = errors.map(error => `<div>${error}</div>`).join("");
                    errorContainer.style.display = "block";
                }
            } else {
                // If validation passes, submit the form
                this.submit();
            }
        });
    }
    
    // Setup validation for edit project forms
    const editProjectForms = document.querySelectorAll('form[action="/project/edit"]');
    editProjectForms.forEach(function(form) {
        if (!form.id.includes('delete')) { // Skip delete forms
            form.addEventListener('submit', function(e) {
                if (typeof validateProjectForm === 'function' && !validateProjectForm(this)) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    });
    
    // Setup validation for edit task forms
    const editTaskForms = document.querySelectorAll('form[action="/task/edit"]');
    editTaskForms.forEach(function(form) {
        if (!form.id.includes('delete')) { // Skip delete forms
            form.addEventListener('submit', function(e) {
                // Always prevent default submission first
                e.preventDefault();
                
                // Reset previous error states
                const errorContainer = form.querySelector(".validation-errors");
                if (errorContainer) {
                    errorContainer.style.display = "none";
                    errorContainer.innerHTML = "";
                }
                
                // Remove previous error styling
                form.querySelectorAll(".is-invalid").forEach(field => {
                    field.classList.remove("is-invalid");
                });
                
                // Check required fields directly in this handler
                let errors = [];
                let hasErrors = false;
                
                // Validate task name
                const nameField = form.querySelector("[name='task_name']");
                if (!nameField || !nameField.value.trim()) {
                    if (nameField) nameField.classList.add("is-invalid");
                    errors.push("Task name is required");
                    hasErrors = true;
                }
                
                // Validate priority
                const priorityField = form.querySelector("[name='task_colour']");
                if (!priorityField || !priorityField.value || priorityField.value.trim() === '') {
                    if (priorityField) priorityField.classList.add("is-invalid");
                    errors.push("Please select a priority");
                    hasErrors = true;
                }
                
                // Validate deadline
                const deadlineField = form.querySelector("[name='deadline']");
                if (!deadlineField || !deadlineField.value) {
                    if (deadlineField) deadlineField.classList.add("is-invalid");
                    errors.push("Deadline is required");
                    hasErrors = true;
                }
                
                // Display errors or submit the form
                if (hasErrors) {
                    if (errorContainer) {
                        errorContainer.innerHTML = errors.map(error => `<div>${error}</div>`).join("");
                        errorContainer.style.display = "block";
                    }
                } else {
                    // Make sure task_status is preserved if present
                    const statusField = form.querySelector("[name='task_status']");
                    if (statusField && !statusField.value) {
                        statusField.value = '1'; // Default to "To do" if empty
                    }
                    
                    // If validation passes, submit the form
                    form.submit();
                }
            });
        }
    });
}

/**
 * Initialize event modal handling for the calendar
 */
function initializeEventModalHandling() {
    // ...calendar-specific code would go here...
} 