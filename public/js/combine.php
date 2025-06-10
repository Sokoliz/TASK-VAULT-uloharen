<?php
// Skript na kombinovanie JavaScript súborov
header('Content-Type: application/javascript');
header('Cache-Control: public, max-age=31536000'); // 1 rok

// Zoznam JavaScript súborov
$files = [
    'theme-switch.js',
    'dynamic-theme.js',
    'event-modal.js',
    'theme.js',
    'clock.js',
    'form-validation.js',
    'modal-handler.js',
    'delete-task-handler.js',
    'delete-project-handler.js',
    'datepicker-init.js'
];

// Získaj verziu súborov z GET parametra alebo nastav default
$version = isset($_GET['v']) ? $_GET['v'] : date('YmdHis');

// Názov cache súboru
$cacheFile = __DIR__ . '/cache/combined-' . $version . '.js';
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
        $combined .= "/* $file */\n" . $content . "\n\n";
    }
}

// Ulož do cache súboru
file_put_contents($cacheFile, $combined);

// Zobraz kombinovaný obsah
echo $combined; 