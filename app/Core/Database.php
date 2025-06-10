<?php
namespace App\Core;

use PDO;
use PDOException;
use App\Config\Config;

class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        try {
            // Pridané PDO parametre pre optimalizáciu
            $options = [
                // Persistent pripojenie
                PDO::ATTR_PERSISTENT => true,
                
                // Správa chýb
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                
                // Optimalizácia výkonu
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                
                // Vypnúť emulované prepared statements pre lepší výkon
                PDO::ATTR_EMULATE_PREPARES => false,
                
                // Zabrániť konverzii stĺpcov na PHP typy
                PDO::ATTR_STRINGIFY_FETCHES => false
            ];
            
            $this->pdo = new PDO(
                'mysql:host=' . $config->get('db.host') . 
                ';dbname=' . $config->get('db.dbname') . 
                ';charset=utf8mb4',  // Pridané explicitné nastavenie kódovania
                $config->get('db.user'),
                $config->get('db.pass'),
                $options
            );
            
        } catch (PDOException $e) {
            // Zlepšené logovanie chýb
            error_log('Database connection error: ' . $e->getMessage());
            die('Databázové pripojenie zlyhalo. Kontaktujte správcu systému.');
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}