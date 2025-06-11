<?php
namespace App\Core;

use App\Controllers\AuthController;
use App\Controllers\ContentController;
use App\Controllers\CalendarController;
use App\Controllers\ProjectController;
use App\Controllers\TaskController;
use App\Controllers\TodayController;
use App\Controllers\IndexViewController;

class Application
{
    private $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function handleStaticFiles()
    {
        $cssCache = 31536000;
        $jsCache = 31536000;
        $imgCache = 31536000;

        $requestUri = $_SERVER['REQUEST_URI'];
        $fileExtension = pathinfo($requestUri, PATHINFO_EXTENSION);

        if (in_array($fileExtension, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf'])) {
            $filePath = __DIR__ . '/../../../' . ltrim($requestUri, '/');
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
                $etag = md5_file($filePath);
                header("ETag: \"$etag\"");
                if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
                    header("HTTP/1.1 304 Not Modified");
                    exit;
                }
                readfile($filePath);
                exit;
            }
        }
    }

    public function setupRoutes()
    {
        $this->router->addRoutes([
            '/' => [IndexViewController::class, 'processRequest'],
            '/register' => [AuthController::class, 'register'],
            '/login' => [AuthController::class, 'login'],
            '/logout' => [AuthController::class, 'logout'],
            '/content' => [ContentController::class, 'index'],
            '/calendar' => [CalendarController::class, 'index'],
            '/calendar/create' => [CalendarController::class, 'create'],
            '/calendar/edit' => [CalendarController::class, 'edit'],
            '/projects' => [ProjectController::class, 'index'],
            '/project/create' => [ProjectController::class, 'create'],
            '/project/edit' => [ProjectController::class, 'edit'],
            '/task/create' => [TaskController::class, 'create'],
            '/task/edit' => [TaskController::class, 'edit'],
            '/task/right' => [TaskController::class, 'move'],
            '/task/left' => [TaskController::class, 'move'],
            '/today' => [TodayController::class, 'index'],
        ]);
        $this->router->addRoute('/check-username', function() {
            require_once './app/Controllers/CheckUsernameController.php';
            $controller = new \CheckUsernameController();
            $controller->handle();
        });
    }

    public function run()
    {
        $this->handleStaticFiles();
        $this->setupRoutes();
        $this->router->dispatch();
    }
} 