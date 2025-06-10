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