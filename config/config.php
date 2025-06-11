<?php
namespace App\Config;

/**
 * Config class
 * 
 * Trieda pre prácu s konfiguráciou aplikácie
 * Používa návrhový vzor Singleton
 */
class Config {
    
    private static $instance = null;
    
    
    private $config = [];
    
    
    private function __construct() {
        // Základná konfigurácia
        $this->config = [
            'db' => [
                'host' => 'localhost',
                'dbname' => 'kanban',
                'user' => 'root',
                'pass' => ''
            ]
        ];
    }
    
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    // Získanie hodnoty z konfigurácie
     
    public function get($key, $default = null) {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return $default;
            }
            
            $value = $value[$k];
        }
        
        return $value;
    }
    
    //Nastavenie hodnoty v konfigurácii
     
    public function set($key, $value) {
        $keys = explode('.', $key);
        $config = &$this->config;
        
        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                $config[$k] = $value;
            } else {
                if (!isset($config[$k]) || !is_array($config[$k])) {
                    $config[$k] = [];
                }
                
                $config = &$config[$k];
            }
        }
        
        return $this;
    }
    
    // Kontrola, či kľúč existuje v konfigurácii
     
    public function has($key) {
        return $this->get($key, $this) !== $this;
    }
    
    //Získanie celej konfigurácie
     
    public function all() {
        return $this->config;
    }
    
    //Načítanie konfigurácie zo súboru
     
    public function loadFromFile($file) {
        if (file_exists($file)) {
            $fileConfig = require $file;
            
            if (is_array($fileConfig)) {
                $this->config = array_merge($this->config, $fileConfig);
            }
        }
        
        return $this;
    }
    
    //Zabránenie klonovaniu (Singleton)
     
    private function __clone() {}
    
    // Zabránenie deserializácii (Singleton)
     
    public function __wakeup() {
        throw new \Exception("Nemôžete deserializovať singleton");
    }
}

// Always return the instance for backwards compatibility
return Config::getInstance();