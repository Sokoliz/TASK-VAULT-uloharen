/**
 * Form submission functions
 * 
 * JavaScript for handling form submissions
 */

/**
 * Submit a form with the given id
 * 
 * @param {string} formId - The ID of the form to submit
 */
function submitForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.submit();
    }
} 