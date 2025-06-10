document.addEventListener("DOMContentLoaded", function() {
    const projectForm = document.querySelector("#new-project-modal form");
    if (projectForm) {
        projectForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Reset previous error states
            const errorContainer = this.querySelector(".validation-errors");
            errorContainer.style.display = "none";
            errorContainer.innerHTML = "";
            
            // Remove previous error styling
            this.querySelectorAll(".is-invalid").forEach(field => {
                field.classList.remove("is-invalid");
            });
            
            // Check required fields
            let errors = [];
            let hasErrors = false;
            
            // Validate project name
            const nameField = this.querySelector("[name='project_name']");
            if (!nameField.value.trim()) {
                nameField.classList.add("is-invalid");
                errors.push("Project name is required");
                hasErrors = true;
            }
            
            // Validate dates
            const startDateField = this.querySelector("[name='start_date']");
            const endDateField = this.querySelector("[name='end_date']");
            
            if (!startDateField.value) {
                startDateField.classList.add("is-invalid");
                errors.push("Start date is required");
                hasErrors = true;
            }
            
            if (!endDateField.value) {
                endDateField.classList.add("is-invalid");
                errors.push("End date is required");
                hasErrors = true;
            }
            
            if (startDateField.value && endDateField.value) {
                const startDate = new Date(startDateField.value);
                const endDate = new Date(endDateField.value);
                
                if (startDate > endDate) {
                    startDateField.classList.add("is-invalid");
                    endDateField.classList.add("is-invalid");
                    errors.push("Start date must be before end date");
                    hasErrors = true;
                }
            }
            
            // Display errors or submit the form
            if (hasErrors) {
                errorContainer.innerHTML = errors.map(error => `<div>${error}</div>`).join("");
                errorContainer.style.display = "block";
            } else {
                this.submit();
            }
        });
    }
}); 