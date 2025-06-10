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