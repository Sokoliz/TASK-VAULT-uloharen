<?php
    // Trieda na pripojenie k databaze - toto som spravil aby som sa nemusel stale pripajat
    class Database{
        // Nastavenia pre databazu - localhost lebo mam XAMPP
        private $hostname = 'localhost';
        private $username = 'root';
        private $password = '';
        private $database = 'kanban';
        private $connection;

        // Metoda na pripojenie k databaze - vrati spojenie
        public function connect(){
            $this->connection = null;
            try
            {
                // PDO je lepsie ako mysqli
                $this->connection = new PDO('mysql:host=' . $this->hostname . ';dbname=' . $this->database . ';charset=utf8', 
                $this->username, $this->password);
            }
            catch(Exception $e)
            {
                // Ak sa nepodari pripojit, vypise chybu
                die('Erro : '.$e->getMessage());
            }

            return $this->connection;
        }
    }
?>
