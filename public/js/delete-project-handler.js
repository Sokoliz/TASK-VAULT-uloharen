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