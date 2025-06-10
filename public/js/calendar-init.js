/**
 * Calendar initialization JavaScript
 * 
 * This file initializes the calendar with dynamic data from PHP
 * The PHP will set calendarEvents and currentDate before including this file
 */

// These variables will be set by PHP before including this file:
// const currentDate = "YYYY-MM-DD";
// const calendarEvents = [...];

// Make sure the variables exist
if (typeof calendarEvents !== 'undefined' && typeof currentDate !== 'undefined') {
    console.log("Calendar initialization with " + calendarEvents.length + " events");
    
    // You can put additional calendar initialization code here
    // This code will run after the calendar.js file is loaded
} 