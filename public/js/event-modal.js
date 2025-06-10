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
 