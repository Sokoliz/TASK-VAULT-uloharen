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