<?php
namespace App\Core;

use PDO;
use PDOException;

// Trieda pre správu pripojenia k databáze implementujúca návrhový vzor Singleton
class Database
{
    // Statická premenná pre uchovanie jedinej inštancie triedy
    private static $instance = null;
    // PDO objekt pre pripojenie k databáze
    private $pdo;

    // Privátny konštruktor zabraňuje vytváraniu viacerých inštancií
    private function __construct()
    {
        // Načítanie konfigurácie databázy zo súboru
        $config = require __DIR__ . '/../../config/config.php';
        try {
            // Vytvorenie PDO pripojenia s parametrami z konfigurácie
            $this->pdo = new PDO(
                'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'],
                $config['db']['user'],
                $config['db']['pass']
            );
            // Nastavenie režimu chýb na vyhodenie výnimiek
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Ukončenie skriptu v prípade chyby pripojenia
            die('DB Connection failed: ' . $e->getMessage());
        }
    }

    // Statická metóda pre získanie PDO inštancie - implementácia Singletonu
    public static function getInstance(): PDO
    {
        // Ak inštancia ešte neexistuje, vytvorí sa nová
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        // Vrátenie PDO objektu pre prácu s databázou
        return self::$instance->pdo;
    }
}