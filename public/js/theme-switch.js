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