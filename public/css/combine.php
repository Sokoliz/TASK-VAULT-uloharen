<?php
// Skript na kombinovanie CSS súborov
header('Content-Type: text/css');
header('Cache-Control: public, max-age=31536000'); // 1 rok

// Zoznam CSS súborov
$files = [
    'style.css',
    'dynamic-theme.css',
    'components.css',
    'theme-icons.css',
    'select-colors.css',
    'modal-colors.css'
];

// Získaj verziu súborov z GET parametra alebo nastav default
$version = isset($_GET['v']) ? $_GET['v'] : date('YmdHis');

// Názov cache súboru
$cacheFile = __DIR__ . '/cache/combined-' . $version . '.css';
$cacheDir = __DIR__ . '/cache';

// Vytvor cache adresár, ak neexistuje
if (!file_exists($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

// Použi cache, ak existuje
if (file_exists($cacheFile)) {
    readfile($cacheFile);
    exit;
}

// Kombinuj súbory
$combined = '';
foreach ($files as $file) {
    $filePath = __DIR__ . '/' . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        // Oprav relatívne cesty v CSS
        $content = preg_replace('/url\([\'"]?([^\/\'"][^\'")]+)[\'"]?\)/i', 'url(../$1)', $content);

        $combined .= "/* $file */\n" . $content . "\n\n";
    }
}

// Ulož do cache súboru
file_put_contents($cacheFile, $combined);

// Zobraz kombinovaný obsah
echo $combined; 