/* theme-switch.js */
/**
 * Theme Switch Functionality
 * 
 * JavaScript pre prepínanie medzi svetlým a tmavým režimom
 */

// Kontrola, či je zapnutý tmavý režim
document.addEventListener('DOMContentLoaded', () => {
    // Skontrolujeme, či používateľ už má nastavený tmavý režim
    const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
    
    // Ak áno, aplikujeme ho
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        const themeSwitch = document.getElementById('theme-switch');
        if (themeSwitch) {
            themeSwitch.checked = true;
        }
    }
    
    // Pridáme event listener na prepínač režimov
    const themeSwitch = document.getElementById('theme-switch');
    if (themeSwitch) {
        themeSwitch.addEventListener('change', function(e) {
            if (e.target.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('theme', 'dark');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('theme', 'light');
            }
        });
    }
});

// Skript pre okamžité načítanie témy z localStorage ešte pred načítaním DOM
(function() {
    var currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
        document.documentElement.style.backgroundColor = '#1f2937';
        document.body ? document.body.classList.add('dark-mode') : 
            document.addEventListener('DOMContentLoaded', function() {
                document.body.classList.add('dark-mode');
            });
    }
})(); 

/* dynamic-theme.js */
/**
 * Dynamic Theme JavaScript
 * Handles updating CSS variables based on user theme preferences
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme based on saved preference
    initTheme();
    
    // Setup theme toggle button
    setupThemeToggle();
    
    // Add event listener for theme icon click (alternative toggle method)
    setupIconToggle();
});

/**
 * Initialize theme based on localStorage
 */
function initTheme() {
    const currentTheme = localStorage.getItem('theme') || 'light';
    const isDark = currentTheme === 'dark';
    
    // Apply theme class
    if (isDark) {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
    
    // Update any toggles
    const themeSwitch = document.getElementById('theme-switch');
    if (themeSwitch) {
        themeSwitch.checked = isDark;
    }
    
    // Always ensure moon icon is present
    const modeIcon = document.querySelector('.mode-icon');
    if (modeIcon) {
        modeIcon.classList.remove('fa-sun');
        modeIcon.classList.add('fa-moon');
    }
}

/**
 * Setup theme toggle based on checkbox
 */
function setupThemeToggle() {
    const themeSwitch = document.getElementById('theme-switch');
    if (themeSwitch) {
        themeSwitch.addEventListener('change', function(e) {
            toggleTheme(e.target.checked);
        });
    }
}

/**
 * Setup theme toggle based on icon click
 */
function setupIconToggle() {
    const modeIcon = document.querySelector('.mode-icon');
    const modeText = document.querySelector('.mode-text');
    
    if (modeIcon) {
        modeIcon.addEventListener('click', function() {
            const isDark = document.body.classList.contains('dark-mode');
            toggleTheme(!isDark);
        });
    }
    
    if (modeText) {
        modeText.addEventListener('click', function() {
            const isDark = document.body.classList.contains('dark-mode');
            toggleTheme(!isDark);
        });
    }
}

/**
 * Toggle theme state
 */
function toggleTheme(darkMode) {
    if (darkMode) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
    } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
    }
    
    // Always ensure moon icon is present
    const modeIcon = document.querySelector('.mode-icon');
    if (modeIcon) {
        modeIcon.classList.remove('fa-sun');
        modeIcon.classList.add('fa-moon');
    }
    
    // Update checkbox if present
    const themeSwitch = document.getElementById('theme-switch');
    if (themeSwitch) {
        themeSwitch.checked = darkMode;
    }
}

/**
 * Setup page-specific theme based on body classes
 */
function setupPageThemes() {
    // Get current page type from body class
    const bodyClasses = document.body.classList;
    
    // Apply theme based on current page
    updatePageTheme(bodyClasses.contains('dark-mode'));
}

/**
 * Update CSS variables based on current page and theme
 * @param {boolean} isDarkMode - Whether dark mode is active
 */
function updatePageTheme(isDarkMode) {
    // Base paths to image directories
    const baseImagePath = '/public/img/backgrounds/';
    
    // Get current page type
    let pageName = '';
    const bodyClasses = document.body.classList;
    
    // Check for specific page classes
    if (bodyClasses.contains('page-login')) pageName = 'login';
    else if (bodyClasses.contains('page-register')) pageName = 'register';
    else if (bodyClasses.contains('page-calendar')) pageName = 'calendar';
    else if (bodyClasses.contains('page-projects')) pageName = 'projects';
    else if (bodyClasses.contains('page-tasks')) pageName = 'tasks';
    else pageName = 'main'; // Default
    
    // Set background image path based on page
    const backgroundPath = baseImagePath + pageName + '.jpg';
    document.documentElement.style.setProperty('--background-image', `url('${backgroundPath}')`);
    
    // Set overlay color based on theme
    const overlayColor = isDarkMode ? 'rgba(0, 0, 0, 0.7)' : 'rgba(255, 255, 255, 0)';
    document.documentElement.style.setProperty('--background-overlay', overlayColor);
    
    // Set text color based on theme
    const textColor = isDarkMode ? '#f8f9fa' : '#212529';
    document.documentElement.style.setProperty('--text-color', textColor);
    
    // Set link color based on theme
    const linkColor = isDarkMode ? '#5bc0de' : '#0275d8';
    document.documentElement.style.setProperty('--link-color', linkColor);
    
    // Set card background based on theme
    const cardBg = isDarkMode ? '#343a40' : '#ffffff';
    document.documentElement.style.setProperty('--card-bg', cardBg);
    
    // Set card border based on theme
    const cardBorder = isDarkMode ? '#495057' : '#dee2e6';
    document.documentElement.style.setProperty('--card-border', cardBorder);
} 

/* event-modal.js */
/**
 * Event Modal Functionality
 * 
 * JavaScript code for managing the event edit modal
 */

$(document).ready(function() {
    console.log("Event modal JS loaded");
    
    // Initialize color styling for selects on page load
    function initializeColorSelects() {
        $('select[name="colour"], select[name="project_colour"], select[name="task_colour"]').each(function() {
            var select = $(this);
            var selectedOption = select.find('option:selected');
            var selectedColor = selectedOption.val();
            
            if (selectedColor) {
                // Apply color to the select element
                select.css({
                    'color': selectedColor,
                    'font-weight': 'bold',
                    'background-color': '#fff'
                });
                
                // Make sure the option text has the color square
                updateColorOptionText(select, selectedColor);
            }
        });
    }
    
    // Function to update color option text
    function updateColorOptionText(select, selectedColor) {
        var colorText = '';
        if (selectedColor === '#5cb85c') {
            colorText = select.attr('name').includes('task') ? 'Low' : 'Green';
        } else if (selectedColor === '#f0ad4e') {
            colorText = select.attr('name').includes('task') ? 'Medium' : 'Orange';
        } else if (selectedColor === '#d9534f') {
            colorText = select.attr('name').includes('task') ? 'High' : 'Red';
        } else if (selectedColor === '#0275d8') {
            colorText = 'Blue';
        } else if (selectedColor === '#5bc0de') {
            colorText = 'Tile';
        } else if (selectedColor === '#292b2c') {
            colorText = 'Black';
        }
        
        select.find('option:selected').html('<span style="color:' + selectedColor + '; font-weight:bold;">&#9724;</span> ' + colorText);
    }
    
    // Initialize colors on page load
    initializeColorSelects();
    
    // Initialize colors when modals are shown
    $('#ModalAdd, #ModalEdit, #new-project-modal, #new-task-modal, #edit-project-modal, #edit-task-modal').on('shown.bs.modal', function() {
        initializeColorSelects();
        
        // Clear validation messages when modal is shown
        $(this).find('.text-danger').remove();
        $(this).find('.is-invalid').removeClass('is-invalid');
    });
    
    // Add input validation for title field
    $('input[name="title"]').on('blur', function() {
        validateTitleField($(this));
    });
    
    // Function to validate title field
    function validateTitleField(field) {
        // Remove existing error messages
        field.removeClass('is-invalid');
        var validationMessage = field.parent().find('.validation-message');
        validationMessage.html('');
        
        // Check if field is empty
        if (!field.val().trim()) {
            field.addClass('is-invalid');
            validationMessage.html('<div class="text-danger">Event name is required</div>');
            return false;
        }
        return true;
    }
    
    // Aktualizuj farbu výberového poľa pri zmene
    $('select[name="colour"], select[name="project_colour"], select[name="task_colour"]').on('change', function() {
        var selectedColor = $(this).val();
        
        // Nastav farbu poľa podľa vybranej hodnoty s !important a tučným písmom
        $(this).css({
            'color': selectedColor,
            'font-weight': 'bold',
            'background-color': '#fff'
        });
        
        updateColorOptionText($(this), selectedColor);
        
        console.log("Color changed to:", selectedColor);
    });
    
    // Validate form on submit
    $("#ModalAdd form, #ModalEdit form").on("submit", function(e) {
        var titleField = $(this).find('input[name="title"]');
        var isValid = validateTitleField(titleField);
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
        
        // Continue with the rest of validation
        return validaForm(this);
    });
    
    // Skontrolujme, či formulár existuje
    console.log("Edit form exists:", $("#ModalEdit form").length > 0);
    console.log("ID field exists:", $("#ModalEdit #id_event").length > 0);
    
    // Kontrola stavu ID pri otvorení modálneho okna
    $('#ModalEdit').on('show.bs.modal', function() {
        var currentId = $("#ModalEdit #id_event").val();
        console.log("Modal opened, current ID:", currentId);
    });
    
    // Handle form submission for event modal
    $("#ModalEdit form").on("submit", function(e) {
        // Check if this is the delete button click
        if (e.originalEvent && e.originalEvent.submitter && e.originalEvent.submitter.name === 'delete') {
            // For delete button, allow the form to submit normally without confirmation
            console.log("Delete button clicked, submitting form");
            return true;
        }
        
        // Otherwise normal validation for the save button
        console.log("Save button clicked, validating form");
        var title = $("#ModalEdit #title").val();
        
        if (!title) {
            $(".validation-message").html("Event name is required").css("color", "red");
            e.preventDefault();
            return false;
        }
        
        // Validate dates
        var startDate = new Date($("#ModalEdit #start_date").val());
        var endDate = new Date($("#ModalEdit #end_date").val());
        var colour = $("#ModalEdit #colour").val();
        
        // Kontrola farby
        if (!colour) {
            alert("Please select a colour for the event.");
            e.preventDefault();
            return false;
        }
        
        if (startDate > endDate) {
            alert("The start date has to be before the end date.");
            e.preventDefault();
            return false;
        }
        
        return true;
    });
    
    // Debug: Kontrola obsahu ID pre všetky viditeľné udalosti v kalendári
    setTimeout(function() {
        var events = $('#calendar').fullCalendar('clientEvents');
        if (events) {
            console.log("Total events in calendar:", events.length);
            events.forEach(function(event, index) {
                console.log("Event", index, "ID:", event.id, "Title:", event.title);
            });
        }
    }, 1000);
});

// Helper function to validate form
function validaForm(form) {
    console.log("validaForm called");
    
    // If delete is checked, no need to validate other fields
    if (form.delete && form.delete.checked) {
        console.log("Delete is checked in validaForm");
        return true;
    }
    
    // Title validation is now handled by validateTitleField function
    
    // Kontrola, či je farba vybraná
    if (form.colour && !form.colour.value) {
        alert("Please select a colour for the event.");
        return false;
    }
    
    // Validate dates
    if (form.start_date && form.end_date) {
        var startDate = new Date(form.start_date.value);
        var endDate = new Date(form.end_date.value);
        
        if (startDate > endDate) {
            alert("The start date has to be before the end date.");
            return false;
        }
    }
    
    return true;
}
 

/* theme.js */
// Funkcia pre prepínanie tmavého režimu
document.addEventListener('DOMContentLoaded', function() {
    console.log('Theme.js loaded');
    
    // Načítam aktuálne nastavenie z localStorage
    const currentTheme = localStorage.getItem('theme');
    console.log('Current theme from localStorage:', currentTheme);
    
    // Nastavím príslušný stav a vzhľad stránky
    if (currentTheme === 'dark') {
        document.body.classList.add('dark-mode');
        console.log('Dark mode activated on load');
    }
    
    // Pridám click handler pre všetko v theme-switch-wrapper
    document.addEventListener('click', function(e) {
        // Kontrolujem, či sme klikli na niečo súvisiace s prepínačom témy
        if (e.target.closest('.theme-switch-wrapper') || 
            e.target.classList.contains('mode-text') || 
            e.target.classList.contains('mode-icon') ||
            e.target.id === 'toggle-button-ui') {
            
            console.log('Theme switch element clicked:', e.target);
            switchTheme();
            e.preventDefault();
        }
    });
    
    // Inicializácia drag and drop pre úlohy, ak existujú
    if (typeof initDragAndDrop === 'function') {
        initDragAndDrop();
    }
    
    // Inicializácia navigačných tlačidiel pre úlohy, ak existujú
    if (typeof initTaskNavigation === 'function') {
        initTaskNavigation();
    }
});

// Funkcia na okamžitú aktualizáciu vizuálu slidera
function updateSliderVisuals(e) {
    const slider = e.target;
    const value = slider.value;
    
    if (parseInt(value) === 1) {
        document.body.classList.add('dark-mode');
        document.documentElement.setAttribute('data-theme', 'dark');
    } else {
        document.body.classList.remove('dark-mode');
        document.documentElement.setAttribute('data-theme', 'light');
    }
}

// Funkcia pre inicializáciu drag and drop
function initDragAndDrop() {
    const draggables = document.querySelectorAll('.draggable');
    const containers = document.querySelectorAll('.task-container');
    
    // Pridáme event listenery na všetky draggable elementy
    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', () => {
            draggable.classList.add('dragging');
        });
        
        draggable.addEventListener('dragend', () => {
            draggable.classList.remove('dragging');
            
            // Získame ID projektu z URL
            const urlParams = new URLSearchParams(window.location.search);
            const projectId = urlParams.get('idProject');
            
            // Získame ID úlohy a nový status
            const taskId = draggable.getAttribute('data-task-id');
            const newStatus = draggable.parentElement.getAttribute('data-status');
            
            // Aktualizujeme status úlohy cez AJAX
            updateTaskStatus(taskId, newStatus, projectId);
        });
    });
    
    // Pridáme event listenery na kontajnery
    containers.forEach(container => {
        container.addEventListener('dragover', e => {
            e.preventDefault();
            container.classList.add('drag-over');
            const draggable = document.querySelector('.dragging');
            container.appendChild(draggable);
        });
        
        container.addEventListener('dragleave', () => {
            container.classList.remove('drag-over');
        });
        
        container.addEventListener('drop', () => {
            container.classList.remove('drag-over');
        });
    });
}

// Funkcia pre aktualizáciu statusu úlohy cez AJAX
function updateTaskStatus(taskId, newStatus, projectId) {
    // Vytvoríme form data
    const formData = new FormData();
    formData.append('id_task', taskId);
    formData.append('task_status', newStatus);
    formData.append('id_project', projectId);
    formData.append('update_status', true);
    
    // Pošleme AJAX request
    fetch('task_update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Zobrazíme potvrdenie
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Úloha aktualizovaná',
                showConfirmButton: false,
                timer: 800
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Funkcia pre inicializáciu navigačných tlačidiel pre úlohy
function initTaskNavigation() {
    const tasks = document.querySelectorAll('.task-item');
    const statuses = ["TO DO", "IN PROGRESS", "COMPLETE"];
    
    // Pridáme navigačné tlačidlá do každej úlohy
    tasks.forEach(task => {
        // Vytvoríme navigačné tlačidlá
        const navButtons = document.createElement('div');
        navButtons.className = 'task-nav-buttons';
        
        // Tlačidlo pre posun doľava
        const leftButton = document.createElement('button');
        leftButton.className = 'task-nav-button';
        leftButton.innerHTML = '<i class="fas fa-arrow-left"></i>';
        
        // Tlačidlo pre posun doprava
        const rightButton = document.createElement('button');
        rightButton.className = 'task-nav-button';
        rightButton.innerHTML = '<i class="fas fa-arrow-right"></i>';
        
        // Pridáme event listenery
        const currentStatus = parseInt(task.getAttribute('data-status'));
        
        // Deaktivujeme tlačidlo, ak sme na kraji
        if (currentStatus === 1) {
            leftButton.disabled = true;
        }
        
        if (currentStatus === 3) {
            rightButton.disabled = true;
        }
        
        // Pridáme funkcionalitu pre posun doľava
        leftButton.addEventListener('click', () => {
            if (currentStatus > 1) {
                // Získame údaje
                const taskId = task.getAttribute('data-task-id');
                const projectId = new URLSearchParams(window.location.search).get('idProject');
                
                // Vytvoríme formulár a pridáme doň údaje
                const form = document.createElement('form');
                form.method = 'post';
                form.style.display = 'none';
                
                const taskIdInput = document.createElement('input');
                taskIdInput.name = 'id_task_left';
                taskIdInput.value = taskId;
                
                const projectIdInput = document.createElement('input');
                projectIdInput.name = 'id_project_left';
                projectIdInput.value = projectId;
                
                const taskStatusInput = document.createElement('input');
                taskStatusInput.name = 'task_status';
                taskStatusInput.value = currentStatus;
                
                form.appendChild(taskIdInput);
                form.appendChild(projectIdInput);
                form.appendChild(taskStatusInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Pridáme funkcionalitu pre posun doprava
        rightButton.addEventListener('click', () => {
            if (currentStatus < 3) {
                // Získame údaje
                const taskId = task.getAttribute('data-task-id');
                const projectId = new URLSearchParams(window.location.search).get('idProject');
                
                // Vytvoríme formulár a pridáme doň údaje
                const form = document.createElement('form');
                form.method = 'post';
                form.style.display = 'none';
                
                const taskIdInput = document.createElement('input');
                taskIdInput.name = 'id_task_right';
                taskIdInput.value = taskId;
                
                const projectIdInput = document.createElement('input');
                projectIdInput.name = 'id_project_right';
                projectIdInput.value = projectId;
                
                const taskStatusInput = document.createElement('input');
                taskStatusInput.name = 'task_status';
                taskStatusInput.value = currentStatus;
                
                form.appendChild(taskIdInput);
                form.appendChild(projectIdInput);
                form.appendChild(taskStatusInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
        
        // Pridáme tlačidlá do navigačného panelu
        navButtons.appendChild(leftButton);
        navButtons.appendChild(rightButton);
        
        // Pridáme navigačný panel do úlohy
        task.appendChild(navButtons);
    });
}

// Jednoduchá funkcia pre prepínanie témy
function switchTheme() {
    console.log('switchTheme called');
    
    if (document.body.classList.contains('dark-mode')) {
        // Prepnutie z tmavého na svetlý režim
        document.body.classList.remove('dark-mode');
        localStorage.setItem('theme', 'light');
        console.log('Switched to light mode');
        document.documentElement.style.backgroundColor = '';
        console.log('Background color reset');
    } else {
        // Prepnutie zo svetlého na tmavý režim
        document.body.classList.add('dark-mode');
        localStorage.setItem('theme', 'dark');
        console.log('Switched to dark mode');
        document.documentElement.style.backgroundColor = '#1f2937';
        console.log('Background color set to dark');
    }
} 

/* clock.js */
/**
 * Clock functionality
 * 
 * JavaScript for displaying the current time
 */

// Update clock display
function updateClock() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    
    const timeString = `${hours}:${minutes}:${seconds}`;
    
    // Update all elements with class 'clock'
    const clockElements = document.querySelectorAll('.clock');
    clockElements.forEach(element => {
        element.textContent = timeString;
    });
}

// Initialize clock on page load
document.addEventListener('DOMContentLoaded', function() {
    // Update immediately
    updateClock();
    
    // Update every second
    setInterval(updateClock, 1000);
}); 

/* form-validation.js */
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

/* modal-handler.js */
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

/* delete-task-handler.js */
/**
 * Task Delete Handler
 * Ensures that task deletion forms are properly submitted
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log("Delete task handler initialized");
    
    // Find all task delete forms
    const taskDeleteForms = document.querySelectorAll('form[action="/task/edit"][name="task"]');
    
    taskDeleteForms.forEach(function(form, index) {
        console.log("Found delete form #" + index);
        
        // Add submit event handler
        form.addEventListener('submit', function(event) {
            // Prevent default form submission for debugging
            // event.preventDefault();
            
            // Log form data
            console.log("Submitting delete form with task ID:", this.querySelector('[name="id_task"]').value);
            
            // Make sure delete flag is set
            const deleteInput = this.querySelector('[name="delete"]');
            if (!deleteInput || deleteInput.value !== '1') {
                console.error("Delete flag not set properly");
                // Add it if missing
                if (!deleteInput) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete';
                    input.value = '1';
                    this.appendChild(input);
                    console.log("Added missing delete flag");
                } else {
                    deleteInput.value = '1';
                    console.log("Corrected delete flag value");
                }
            }
            
            // Allow form to submit naturally
            console.log("Delete form submission proceeding");
        });
    });
}); 

/* delete-project-handler.js */
/**
 * Project Delete Handler
 * Ensures that project deletion forms are properly submitted
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log("Delete project handler initialized");
    
    // Find all project delete forms
    const projectDeleteForms = document.querySelectorAll('form[action="/project/edit"][id^="delete-project-form"]');
    
    projectDeleteForms.forEach(function(form, index) {
        console.log("Found project delete form #" + index);
        
        // Add submit event handler
        form.addEventListener('submit', function(event) {
            // Log form data
            console.log("Submitting project delete form with project ID:", this.querySelector('[name="id_project"]').value);
            
            // Make sure delete flag is set
            const deleteInput = this.querySelector('[name="delete"]');
            if (!deleteInput || deleteInput.value !== '1') {
                console.error("Delete flag not set properly");
                // Add it if missing
                if (!deleteInput) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete';
                    input.value = '1';
                    this.appendChild(input);
                    console.log("Added missing delete flag");
                } else {
                    deleteInput.value = '1';
                    console.log("Corrected delete flag value");
                }
            }
            
            // Allow form to submit naturally
            console.log("Project delete form submission proceeding");
        });
    });
}); 

/* datepicker-init.js */
/**
 * Datepicker Initialization
 * Handles the initialization and configuration of datepicker fields for project forms
 */

$(document).ready(function () {
    // Debug code for form submission
    console.log("Document ready - initializing project form debugging");
    $("#new-project-modal form").on("submit", function(e) {
        console.log("Project form submitted");
        var formData = {};
        $(this).serializeArray().forEach(function(item) {
            formData[item.name] = item.value;
        });
        console.log("Form data:", formData);
    });
    
    // First set of date pickers
    $("#startAdd").datepicker({
        todayBtn: 1,
        autoclose: true,
        format: "yyyy-mm-dd",
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endAdd').datepicker('setStartDate', minDate);
    });
    
    $("#endAdd").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#startAdd').datepicker('setEndDate', minDate);
    });
    
    // Second set of date pickers
    $("#startAdd1").datepicker({
        todayBtn: 1,
        autoclose: true,
        format: "yyyy-mm-dd",
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endAdd1').datepicker('setStartDate', minDate);
    });
    
    $("#endAdd1").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#startAdd1').datepicker('setEndDate', minDate);
    });
    
    // Third set of date pickers
    $("#startAdd2").datepicker({
        todayBtn: 1,
        autoclose: true,
        format: "yyyy-mm-dd",
        minDate: 0,
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#endAdd2').datepicker('setStartDate', minDate);
    });
    
    $("#endAdd2").datepicker({
        format: "yyyy-mm-dd",
        autoclose: true
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        $('#startAdd2').datepicker('setEndDate', minDate);
    });
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
}); 

