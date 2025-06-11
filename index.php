<?php
require_once __DIR__ . '/vendor/autoload.php';

// Optimalizácia - cachovanie pre statické súbory
$cssCache = 31536000; // 1 rok v sekundách
$jsCache = 31536000;  // 1 rok v sekundách
$imgCache = 31536000; // 1 rok v sekundách

$requestUri = $_SERVER['REQUEST_URI'];
$fileExtension = pathinfo($requestUri, PATHINFO_EXTENSION);

// Nastav cache headery pre statické súbory
if (in_array($fileExtension, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf'])) {
    $filePath = __DIR__ . $requestUri;
    if (file_exists($filePath)) {
        switch ($fileExtension) {
            case 'css':
                header('Content-Type: text/css');
                header('Cache-Control: public, max-age=' . $cssCache);
                break;
            case 'js':
                header('Content-Type: application/javascript');
                header('Cache-Control: public, max-age=' . $jsCache);
                break;
            case 'jpg':
            case 'jpeg':
                header('Content-Type: image/jpeg');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
            case 'png':
                header('Content-Type: image/png');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
            case 'gif':
                header('Content-Type: image/gif');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
            case 'svg':
                header('Content-Type: image/svg+xml');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
            case 'woff':
                header('Content-Type: font/woff');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
            case 'woff2':
                header('Content-Type: font/woff2');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
            case 'ttf':
                header('Content-Type: font/ttf');
                header('Cache-Control: public, max-age=' . $imgCache);
                break;
        }
        
        // Použi ETag pre optimalizáciu
        $etag = md5_file($filePath);
        header("ETag: \"$etag\"");
        
        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
            // Súbor sa nezmenil, klient má aktuálnu verziu
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
        
        // Výstup statického súboru
        readfile($filePath);
        exit;
    }
}

use App\Controllers\AuthController;
use App\Controllers\ContentController;
use App\Controllers\CalendarController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;
use App\Controllers\TodayController;
use App\Controllers\IndexViewController;
use App\Core\Router;

// Vytvoríme inštanciu routera - ten sa stará o smerovanie URL adries
$router = new Router();

// Definujeme cesty - každá URL má priradenú triedu a metódu, ktorá sa zavolá
$router->addRoutes([
    // Základná cesta - domovská stránka
    '/' => [IndexViewController::class, 'processRequest'],
    
    // Autentifikačné cesty - prihlásenie, registrácia, odhlásenie
    '/register' => [AuthController::class, 'register'],
    '/login' => [AuthController::class, 'login'],
    '/logout' => [AuthController::class, 'logout'],
    
    // Cesty pre obsah - hlavná časť aplikácie
    '/content' => [ContentController::class, 'index'],
    
    // Cesty pre kalendár - zobrazenie, vytváranie a úprava udalostí
    '/calendar' => [CalendarController::class, 'index'],
    '/calendar/create' => [CalendarController::class, 'create'],
    '/calendar/edit' => [CalendarController::class, 'edit'],
    
    // Cesty pre projekty - kanban board a správa projektov
    '/projects' => [ProjectController::class, 'index'],
    '/project/create' => [ProjectController::class, 'create'],
    '/project/edit' => [ProjectController::class, 'edit'],
    
    // Cesty pre úlohy - vytváranie, úprava a presúvanie medzi stĺpcami
    '/task/create' => [TaskController::class, 'create'],
    '/task/edit' => [TaskController::class, 'edit'],
    '/task/right' => [TaskController::class, 'move'],
    '/task/left' => [TaskController::class, 'move'],
    
    // Cesty pre dnešné aktivity - prehľad toho, čo je dnes na pláne
    '/today' => [TodayController::class, 'index'],
]);

// Pridáme špeciálnu cestu pre AJAX kontrolu používateľského mena pri registrácii
$router->addRoute('/check-username', function() {
    require_once './app/Controllers/CheckUsernameController.php';
    $controller = new CheckUsernameController();
    $controller->handle();
});

// Spustíme spracovanie požiadavky - router rozhodne, ktorý kontrolér sa má zavolať
$router->dispatch();