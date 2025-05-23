<?php
// Import konfiguračných funkcií pre prácu s obrázkami
require_once 'config/functions.php';

// Spustenie session pre správu prihláseného používateľa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kontrola, či je stránka verejná (voliteľný parameter)
$public_page = isset($public_page) ? $public_page : false;

// Presmerovanie neprihlásených používateľov na hlavnú stránku
if (!$public_page && !isset($_SESSION['user'])) {
    header('Location: main.php');
    die();
}
?>	
	
<!-- Meta tagy pre správne zobrazenie stránky -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<!-- Bootstrap framework pre štýly -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" media='all' integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<!-- Importované písma -->
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Comfortaa&display=swap" rel="stylesheet">

<!-- Vlastné CSS štýly -->
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="dynamic-styles.php">
<title><?php echo isset($title) ? $title : 'Productivity Hub'; ?></title>

<!-- JavaScript pre tmavý režim -->
<script>
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
</script>

