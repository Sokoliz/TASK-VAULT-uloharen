<?php
// Nastavenie typu obsahu na CSS
header('Content-Type: text/css');

// Import konfiguračných funkcií pre prácu s obrázkami
require_once __DIR__.'/../../config/functions.php';

// Získanie konfigurácie
$imagesConfig = getImagesConfig();

// Pozadie pre .bg triedu
$bgImagePath = $imagesConfig['backgrounds']['main'];
echo ".bg {\n";
echo "    background-image: url('../$bgImagePath');\n";
echo "}\n\n";

// Tmavý režim
echo ".dark-mode .bg {\n";
echo "    background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../$bgImagePath');\n";
echo "}\n";
?> 