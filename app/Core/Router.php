<?php
namespace App\Core;

use App\Controllers\IndexViewController;

/**
 * Router trieda
 * 
 * Spracováva smerovanie HTTP požiadaviek na príslušné controllery
 */
class Router
{
    
    private $uri;
    
    
    private $routes = [];
    
    
    public function __construct()
    {
        // Získanie cesty z URL adresy
        // Táto funkcia parse_url je super, lebo nemusím ručne parsovať URL
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    
    
    public function addRoute($path, $handler)
    {
        // Pridám cestu do asociatívneho poľa routes
        // Kľúč je cesta a hodnota je handler (controller alebo funkcia)
        $this->routes[$path] = $handler;
        return $this;
    }
    
    /**
     * Pridanie viacerých ciest naraz
     */
    public function addRoutes(array $routes)
    {
        // Iterácia cez pole ciest a pridanie každej z nich
        // Toto je efektívnejšie ako volať addRoute pre každú cestu zvlášť
        foreach ($routes as $path => $handler) {
            $this->addRoute($path, $handler);
        }
        return $this;
    }
    
    /**
     * Odoslanie požiadavky na príslušný controller
     */
    public function dispatch()
    {
        // Kontrola, či existuje handler pre aktuálnu cestu
        // Ak áno, spustím ho, inak spustím defaultný handler
        if (isset($this->routes[$this->uri])) {
            $route = $this->routes[$this->uri];
            
            // Spracovanie string callbacks (špeciálne prípady)
            // Napríklad ak chcem volať metódu priamo v Router triede
            if (is_string($route) && method_exists($this, $route)) {
                return $this->$route();
            }
            
            // Spracovanie array [controller, method]
            // Toto je najbežnejší prípad použitia
            if (is_array($route) && count($route) === 2) {
                list($controller, $method) = $route;
                
                // Spracovanie string class names
                // Vytvorím inštanciu controllera z názvu triedy
                if (is_string($controller)) {
                    $controllerInstance = new $controller();
                } else {
                    $controllerInstance = $controller;
                }
                
                // Zavolám metódu na inštancii controllera
                // Toto je vlastne to podstatné, čo router robí
                return $controllerInstance->$method();
            }
            
            // Spracovanie closures (anonymné funkcie)
            // Toto sa hodí pre jednoduché routy
            if (is_callable($route)) {
                return $route();
            }
        }
        
        // Spracovanie defaultnej cesty
        // Ak nebola nájdená žiadna cesta, zobrazím defaultnú stránku
        return $this->handleDefault();
    }
    
    /**
     * Spracovanie defaultnej cesty
     */
    protected function handleDefault()
    {
        // Použitie IndexViewController namiesto priameho requirovania view
        // Toto je lepšie riešenie z hľadiska MVC architektúry
        $controller = new IndexViewController();
        $controller->processRequest();
    }
} 