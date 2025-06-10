/**
 * Calendar Debug Script
 */
console.log('Calendar debug script loaded');

// Kontrola či je jQuery načítané
if (typeof jQuery !== 'undefined') {
    console.log('jQuery is loaded');
} else {
    console.error('jQuery is NOT loaded');
}

// Kontrola či je Moment.js načítané
if (typeof moment !== 'undefined') {
    console.log('Moment.js is loaded');
} else {
    console.error('Moment.js is NOT loaded');
}

// Kontrola či je FullCalendar načítané
if (typeof $.fn.fullCalendar !== 'undefined') {
    console.log('FullCalendar is loaded');
} else {
    console.error('FullCalendar is NOT loaded');
}

// Kontrola HTML elementov
$(document).ready(function() {
    console.log('Document ready');
    
    if ($('#calendar').length) {
        console.log('Calendar element found');
        console.log('Calendar dimensions:', $('#calendar').width(), 'x', $('#calendar').height());
        console.log('Calendar visibility:', $('#calendar').is(':visible'));
    } else {
        console.error('Calendar element NOT found');
    }
    
    // Kontrola globálnych premenných
    if (typeof calendarEvents !== 'undefined') {
        console.log('calendarEvents global variable is defined', calendarEvents);
    } else {
        console.error('calendarEvents global variable is NOT defined');
    }
    
    if (typeof currentDate !== 'undefined') {
        console.log('currentDate global variable is defined', currentDate);
    } else {
        console.error('currentDate global variable is NOT defined');
    }
});

/**
 * Calendar debugging functionality
 * 
 * JavaScript for debugging calendar functions
 * This file is used for printing debug messages related to the calendar
 */

/**
 * Log the number of events loaded
 * 
 * @param {number} count - Number of events
 */
function logEventsCount(count) {
    console.log("Events count from JavaScript: " + count);
} 