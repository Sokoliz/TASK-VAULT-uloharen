<?php
namespace App\Core;

use App\Controllers\IndexViewController;

/**
 * Router class
 * 
 * Handles the routing of HTTP requests to appropriate controllers
 */
class Router
{
    /**
     * URI to be routed
     * @var string
     */
    private $uri;
    
    /**
     * Routes configuration
     * @var array
     */
    private $routes = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    
    /**
     * Add a route
     * 
     * @param string $path The URL path
     * @param string|array $handler Controller and method
     * @return self
     */
    public function addRoute($path, $handler)
    {
        $this->routes[$path] = $handler;
        return $this;
    }
    
    /**
     * Add multiple routes at once
     * 
     * @param array $routes Array of routes [path => handler]
     * @return self
     */
    public function addRoutes(array $routes)
    {
        foreach ($routes as $path => $handler) {
            $this->addRoute($path, $handler);
        }
        return $this;
    }
    
    /**
     * Dispatch the request to the appropriate controller
     * 
     * @return mixed
     */
    public function dispatch()
    {
        if (isset($this->routes[$this->uri])) {
            $route = $this->routes[$this->uri];
            
            // Handle string callbacks (special cases)
            if (is_string($route) && method_exists($this, $route)) {
                return $this->$route();
            }
            
            // Handle array [controller, method]
            if (is_array($route) && count($route) === 2) {
                list($controller, $method) = $route;
                
                // Handle string class names
                if (is_string($controller)) {
                    $controllerInstance = new $controller();
                } else {
                    $controllerInstance = $controller;
                }
                
                return $controllerInstance->$method();
            }
            
            // Handle closures
            if (is_callable($route)) {
                return $route();
            }
        }
        
        // Default route handler
        return $this->handleDefault();
    }
    
    /**
     * Handle default route
     */
    protected function handleDefault()
    {
        // Use the new IndexViewController instead of directly requiring the view
        $controller = new IndexViewController();
        $controller->processRequest();
    }
} 