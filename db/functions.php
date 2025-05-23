<?php
    // Trieda pre prácu s databázou
    class Database{
        // Nastavenia pripojenia k databáze
        private $hostname = 'localhost';
        private $username = 'root';
        private $password = '';
        private $database = 'kanban';
        private $connection;

        // Metóda na vytvorenie pripojenia k databáze
        public function connection(){
            $this->connection = null;
            try
            {
                // Pokus o vytvorenie PDO pripojenia k MySQL databáze
                $this->connection = new PDO('mysql:host=' . $this->hostname . ';dbname=' . $this->database . ';charset=utf8', 
                $this->username, $this->password);
            }
            catch(Exception $e)
            {
                // V prípade chyby ukončíme skript a zobrazíme chybovú správu
                die('Err : '.$e->getMessage());
            }

            // Vrátime vytvorené pripojenie
            return $this->connection;
        }
    }
?>
