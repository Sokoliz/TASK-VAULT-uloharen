# Teória - Vysvetlenie fungovania TASK-VAULT projektu

## Obsah
1. [Databáza a pripojenie](#databáza-a-pripojenie)
2. [PHP funkcie a príkazy](#php-funkcie-a-príkazy)
3. [CRUD operácie](#crud-operácie)
4. [Bezpečnosť](#bezpečnosť)
5. [Špeciálne funkcie](#špeciálne-funkcie)
6. [Autentifikácia používateľov](#autentifikácia-používateľov)
7. [Štruktúra súborov](#štruktúra-súborov)
8. [Podrobný popis funkcií a príkazov](#podrobný-popis-funkcií-a-príkazov)
9. [Vzťahy medzi súbormi a ich funkcionalita](#vzťahy-medzi-súbormi-a-ich-funkcionalita)

## Databáza a pripojenie

### Štruktúra databázy
Projekt používa MySQL databázu nazvanú `kanban` s nasledujúcimi tabuľkami:

- **users** - uchováva informácie o užívateľoch
  - `id_user` - primárny kľúč, auto increment
  - `user` - prihlasovacie meno (unikátne)
  - `password` - heslo užívateľa 

- **projects** - uchováva informácie o projektoch
  - `id_project` - primárny kľúč, auto increment
  - `id_user` - cudzí kľúč odkazujúci na užívateľa
  - `project_name` - názov projektu (max 30 znakov)
  - `project_description` - popis projektu (max 255 znakov)
  - `project_colour` - farba projektu (hex kód)
  - `start_date` - dátum začiatku projektu
  - `end_date` - dátum konca projektu

- **tasks** - uchováva informácie o úlohách v rámci projektov
  - `id_task` - primárny kľúč, auto increment
  - `id_user` - cudzí kľúč odkazujúci na užívateľa
  - `id_project` - cudzí kľúč odkazujúci na projekt
  - `task_status` - stav úlohy (1-4, reprezentuje stĺpce Kanban tabule)
  - `task_name` - názov úlohy (max 30 znakov)
  - `task_description` - popis úlohy (max 255 znakov)
  - `task_colour` - farba úlohy (hex kód)
  - `deadline` - termín dokončenia

- **calendar** - uchováva informácie o udalostiach v kalendári
  - `id_event` - primárny kľúč, auto increment
  - `id_user` - cudzí kľúč odkazujúci na užívateľa
  - `title` - názov udalosti
  - `description` - popis udalosti
  - `colour` - farba udalosti
  - `start_date` - začiatok udalosti
  - `end_date` - koniec udalosti

### Pripojenie k databáze
V súbore `db/functions.php` je definovaná trieda `Database`, ktorá slúži na vytvorenie pripojenia k databáze:

```php
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
```

**Vysvetlenie príkazov:**
- `class Database` - definuje triedu pre prácu s databázou
- `private $hostname, $username, $password, $database` - privátne premenné triedy, ktoré obsahujú prihlasovacie údaje
- `public function connection()` - verejná metóda, ktorá vracia pripojenie k databáze
- `new PDO()` - vytvorenie nového pripojenia pomocou PHP Data Objects
- `try { ... } catch(Exception $e) { ... }` - zachytenie prípadných chýb pri pripojení
- `die()` - ukončí vykonávanie skriptu a zobrazí chybovú správu
- `return $this->connection` - vráti vytvorené pripojenie

## PHP funkcie a príkazy

### Správa sessions
- `session_start()` - začne novú alebo obnoví existujúcu session; musí byť na začiatku každého PHP súboru, ktorý pracuje so sessions
- `$_SESSION` - superglobálne pole pre ukladanie a prístup k premenným v rámci session
- `session_destroy()` - zruší aktuálnu session (používa sa pri odhlásení)

### Superglobálne premenné
- `$_SESSION` - uchováva údaje počas celej session (napr. informácie o prihlásenom užívateľovi)
- `$_POST` - obsahuje dáta odoslané metódou POST (z formulárov)
- `$_GET` - obsahuje dáta odoslané metódou GET (z URL parametrov)
- `$_SERVER` - obsahuje informácie o serveri a aktuálnej požiadavke
- `$_SERVER['REQUEST_METHOD']` - zisťuje, či ide o GET alebo POST požiadavku
-`$GET`: Používa sa pre operácie, ktoré iba získavajú dáta, filtrujú a zobrazujú výsledky
-`$POST`: Používa sa pre operácie, ktoré vytvárajú, upravujú alebo mažú dáta, a tiež pre odosielanie citlivých údajov

### Práca s dátumom
- `date("Y-m-d", strtotime($date))` - konvertuje string dátum na formát Y-m-d
- `strtotime()` - prevádza textový dátum na UNIX timestamp

### Redirects a ukončenie skriptu
- `header('Location: main.php')` - presmeruje používateľa na inú stránku
- `die()` - ukončí vykonávanie skriptu (často sa používa po presmerovaní)

### Práca s databázou (PDO)
- `$connection->prepare()` - pripraví SQL dotaz s parametrami (ochrana proti SQL injection)
- `$statement->execute(array(...))` - vykoná SQL dotaz a nahradí otázniky v prepare hodnotami z poľa
- `$statement->fetch()` - vráti jeden riadok výsledku
- `$statement->fetchAll()` - vráti všetky riadky výsledku ako pole

### Práca s reťazcami a bezpečnosť
- `filter_var($value, FILTER_SANITIZE_STRING)` - vyčistí string od potenciálne nebezpečných znakov
- `htmlspecialchars()` - konvertuje špeciálne znaky na HTML entity (ochrana proti XSS)
- `isset()` - zisťuje, či premenná existuje a nie je NULL
- `empty()` - zisťuje, či je premenná prázdna
- `(int)$value` - pretypuje hodnotu na integer

### Práca s poľami
- `array()` - vytvára pole
- `count($array)` - vráti počet prvkov v poli
- `implode()` - spája prvky poľa do stringu
- `explode()` - rozdelí string na pole podľa oddeľovača

## CRUD operácie

CRUD operácie sú skratkou pre Create, Read, Update, Delete a predstavujú základné operácie, ktoré sa vykonávajú s dátami v databáze:

- **Create**: Vloženie nového záznamu do databázy (vloženie údajov z formulára do db)
- **Read**: Čítanie a zobrazenie existujúcich záznamov z databázy (čítanie otázok a odpovedí – domáca úloha)
- **Update**: Aktualizácia a úprava existujúcich záznamov v databáze (úprava konkrétnej otázky/odpovede)
- **Delete**: Vymazanie záznamov z databázy.

### Create (Vytváranie)
Príklad vytvorenia nového projektu:

```php
// Uložíme nový projekt do databázy
$statement = $connection->prepare('INSERT INTO projects (id_user, project_name, project_description, project_colour, start_date, end_date) VALUES
(?, ?, ?, ?, ?, ?)');
$statement->execute(array($id_user, $project_name, $project_description, $project_colour, $start_date, $end_date));
```

**Vysvetlenie:**
1. `prepare()` - pripraví SQL dotaz s parametrami (otázniky)
2. `execute()` - vykoná dotaz, pričom nahradí otázniky hodnotami z poľa v poradí
3. Pole `array()` obsahuje hodnoty, ktoré sa vložia namiesto otáznikov

**Súbory implementujúce CREATE operácie:**
- **register.php** - registrácia nových používateľov
  ```php
  $statement = $connection->prepare('INSERT INTO users (id_user, user, password) VALUES (null, :user, :password)');
  ```
- **projects.php** - pridávanie nových projektov a úloh
  ```php
  // Pridávanie projektov
  $statement = $connection->prepare('INSERT INTO projects (id_user, project_name, project_description, project_colour, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)');
  
  // Pridávanie úloh
  $statement = $connection->prepare('INSERT INTO tasks (id_user, id_project, task_status, task_name, task_description, task_colour, deadline) VALUES (?, ?, ?, ?, ?, ?, ?)');
  ```
- **events/actions/eventAdd.php** - pridávanie udalostí do kalendára
  ```php
  $sql = "INSERT INTO calendar(id_user, title, description, start_date, end_date, colour) values ('$id_user', '$title', '$description', '$start_date', '$end_date', '$colour')";
  ```

### Read (Čítanie)
Príklad získania všetkých projektov užívateľa:

```php
// Vyberieme všetky projekty pre aktuálneho užívateľa
$projects = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? ORDER BY id_project DESC");
$projects->execute(array($_SESSION['id_user']));
$projects = $projects->fetchAll();
```

**Vysvetlenie:**
1. `prepare()` - pripraví SQL SELECT dotaz s parametrom (otáznik)
2. `execute()` - vykoná dotaz s konkrétnym ID užívateľa
3. `fetchAll()` - získa všetky riadky výsledku ako pole

**Súbory implementujúce READ operácie:**
- **login.php** - overenie prihlasovacích údajov
  ```php
  $statement = $connection->prepare('SELECT * FROM users WHERE user =? AND password =?');
  ```
- **register.php** - kontrola existencie používateľského mena
  ```php
  $statement = $connection->prepare('SELECT * FROM users WHERE user = :user LIMIT 1');
  ```
- **projects.php** - zobrazenie projektov a úloh
  ```php
  // Získanie zoznamu projektov
  $projects = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? ORDER BY id_project DESC");
  
  // Získanie úloh konkrétneho projektu
  $show_tasks = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE id_user = ? AND id_project = ? ORDER BY deadline DESC");
  ```
- **today.php** - zobrazenie dnešných projektov, úloh a udalostí
  ```php
  // Projekty začínajúce dnes
  $projects_start = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? AND start_date= ? ORDER BY id_project DESC");
  
  // Projekty končiace dnes
  $projects_end = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? AND end_date= ? ORDER BY id_project DESC");
  
  // Úlohy s dnešným termínom
  $tasks = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM tasks WHERE id_user = ? AND deadline= ? ORDER BY id_task DESC");
  ```
- **calendar.php** - zobrazenie udalostí v kalendári
  ```php
  $statement = $connection->prepare("SELECT id_event, title, description, start_date, end_date, colour FROM calendar Where id_user = ? ");
  ```

### Update (Aktualizácia)
Príklad aktualizácie projektu:

```php
// Aktualizujeme údaje v databáze
$statement = $connection->prepare('UPDATE projects SET project_name=?, project_description=?, project_colour=?, start_date=?, end_date=? WHERE id_project=?');
$statement->execute(array($edit_project_name, $edit_project_description, $edit_project_colour, $edit_start_date, $edit_end_date, $edit_id_project));
```

**Vysvetlenie:**
1. `prepare()` - pripraví SQL UPDATE dotaz s parametrami (otázniky)
2. `execute()` - vykoná dotaz, pričom nahradí otázniky hodnotami z poľa
3. Posledný parameter v poli je ID projektu, ktorý sa aktualizuje

**Súbory implementujúce UPDATE operácie:**
- **projects.php** - úprava projektov a úloh
  ```php
  // Úprava projektu
  $statement = $connection->prepare('UPDATE projects SET project_name=?, project_description=?, project_colour=?, start_date=?, end_date=? WHERE id_project=?');
  
  // Úprava úlohy
  $statement = $connection->prepare('UPDATE tasks SET task_name=?, task_description=?, task_colour=?, deadline=? WHERE id_task=?');
  
  // Zmena stavu úlohy (presun medzi stĺpcami Kanban)
  $statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
  ```
- **events/actions/eventEdit.php** - úprava udalostí v kalendári
  ```php
  $sql = "UPDATE calendar SET title = '$title', description = '$description', start_date = '$start_date', end_date = '$end_date', colour = '$colour' WHERE id_event = '$id_event'";
  ```
- **events/actions/eventEditData.php** - aktualizácia dátumov udalosti pri drag & drop
  ```php
  $sql = "UPDATE calendar SET start_date = '$start_date', end_date = '$end_date' WHERE id_event = '$id_event'";
  ```

### Delete (Mazanie)
Príklad zmazania projektu:

```php
// Zmažeme samotný projekt z databázy
$del_project = $connection->prepare("DELETE FROM projects WHERE id_project = ?");
$del_project->execute(array($id_project));
```

**Vysvetlenie:**
1. `prepare()` - pripraví SQL DELETE dotaz s parametrom (otáznik)
2. `execute()` - vykoná dotaz s konkrétnym ID projektu

**Súbory implementujúce DELETE operácie:**
- **projects.php** - mazanie projektov a úloh
  ```php
  // Mazanie projektu
  $del_project = $connection->prepare("DELETE FROM projects WHERE id_project = ?");
  
  // Mazanie všetkých úloh projektu
  $del_tasks = $connection->prepare("DELETE FROM tasks WHERE id_project = ?");
  
  // Mazanie konkrétnej úlohy
  $del_task = $connection->prepare("DELETE FROM tasks WHERE id_task = ?");
  ```
- **delete.php** - univerzálny kontrolér pre mazanie
  ```php
  $del_project_confirm = $connection->prepare('DELETE FROM projects WHERE id_project=?');
  ```
- **events/actions/eventEdit.php** - mazanie udalosti v kalendári
  ```php
  $sql = "DELETE FROM calendar WHERE id_event = '$id_event'";
  ```

## Bezpečnosť

### Ochrana proti SQL injection
Projekt používa PDO s prepare statements, čo zabraňuje SQL injection útokom:

```php
$projects = $connection->prepare("SELECT * FROM projects WHERE id_user = ?");
$projects->execute(array($_SESSION['id_user']));
```

Namiesto priameho vkladania hodnôt do SQL dotazu sa používajú parametre (otázniky), ktoré PDO bezpečne spracuje.

### Ochrana proti XSS (Cross-Site Scripting)
Všetky vstupy od užívateľa sú čistené pomocou `filter_var()` a `htmlspecialchars()`:

```php
$project_name = filter_var(htmlspecialchars($_POST['project_name']), FILTER_SANITIZE_STRING);
```

### Kontrola prihlásenia
V každom chránenom súbore sa kontroluje, či je užívateľ prihlásený:

```php
// Kontrola prihlásenia - neprihlásených užívateľov nepustíme ďalej
if (isset($_SESSION['user'])) {
    // Užívateľ je prihlásený, pokračujeme
} else {
    // Neprihlásených presmerujeme na hlavnú stránku
    header('Location: main.php');
    die();
}
```

## Špeciálne funkcie

### Presúvanie úloh v Kanban tabuľke
Úlohy v projekte môžu mať štyri stavy (stĺpce). Projekt implementuje funkcionalitu presúvania úloh medzi stĺpcami pomocou `task_status`:

```php
// Presun úlohy doprava (zvýšenie status)
$new_status = ((int)$task_status + 1);
$statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
$statement->execute(array($new_status, $id_task_right));

// Presun úlohy doľava (zníženie status)
$new_status = ((int)$task_status - 1);
$statement = $connection->prepare('UPDATE tasks SET task_status=? WHERE id_task=?');
$statement->execute(array($new_status, $id_task_left));
```

### Práca s farebným kódovaním
Projekty a úlohy môžu mať priradenú farbu vo formáte hex kódu (napr. `#0275d8`):

```php
$project_colour = filter_var(htmlspecialchars($_POST['project_colour']), FILTER_SANITIZE_STRING);
```

Farby sa používajú na vizuálne odlíšenie projektov a úloh v užívateľskom rozhraní.

### JavaScript notifikácie
Projekt používa knižnicu SweetAlert pre zobrazovanie notifikácií:

```php
echo '<script language="javascript">';
echo "Swal.fire({
    position: 'center',
    icon: 'success',
    title: 'Congrats!',
    text: 'Your project has been successfully created!',
    showConfirmButton: false,
    timer: 1200,
}).then(function(){ 
    location.href = 'projects.php'				
    });";
echo '</script>';
```

Tieto notifikácie sa zobrazujú po úspešnom vykonaní CRUD operácií. 

## Autentifikácia používateľov

### Prihlasovanie (login.php)

Prihlasovací proces funguje nasledovne:

```php
// Kontrola, či je používateľ už prihlásený
if (isset($_SESSION['user'])) {
	// Ak je používateľ prihlásený, presmeruj ho na hlavnú stránku obsahu
	header('Location: content.php');
	die(); // Ukončí vykonávanie skriptu
}

// Kontrola, či bol odoslaný formulár (metóda POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Filtrovanie a sanitizácia vstupných údajov pre bezpečnosť
	$user_form = filter_var(htmlspecialchars($_POST['user']), FILTER_SANITIZE_STRING);
	$password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
	// Hashovanie hesla pre bezpečné porovnanie s databázou
	$password_form = hash('sha512', $password_form);
	
	// Príprava a vykonanie SQL dotazu na overenie prihlasovacích údajov
	$statement = $connection->prepare('SELECT * FROM users WHERE user =? AND password =?');
	$statement->execute(array($user_form, $password_form));
	$result = $statement->rowCount();
	
	// Kontrola, či bol nájdený používateľ s danými prihlasovacími údajmi
	if ($result == 1) {
		// Získanie ID používateľa a uloženie do session
		while ($id = $statement->fetch(PDO::FETCH_ASSOC)) {
			$id_user = $id['id_user'];
			$_SESSION['id_user'] = $id_user;
			$_SESSION['user'] = $user_form;
		}

		// Presmerovanie na hlavnú stránku po úspešnom prihlásení
		header('Location: index.php');
	}
}
```

**Vysvetlenie kľúčových príkazov:**
- `session_start()` - začne alebo obnoví existujúcu session
- `isset($_SESSION['user'])` - kontroluje, či je používateľ už prihlásený
- `filter_var(htmlspecialchars($_POST['user']), FILTER_SANITIZE_STRING)` - ochrana proti XSS
- `hash('sha512', $password_form)` - vytvára 512-bitový SHA hash hesla pre bezpečné uloženie
- `$statement->rowCount()` - vráti počet riadkov vrátených SQL dopytom (1 = úspešné prihlásenie)
- `$statement->fetch(PDO::FETCH_ASSOC)` - získa jeden riadok výsledku ako asociatívne pole
- `$_SESSION['id_user'] = $id_user` - uloží ID používateľa do session
- `header('Location: index.php')` - presmeruje na hlavnú stránku

### Odhlasovanie (logout.php)

Proces odhlásenia je jednoduchý:

```php
// Štartujeme session
session_start();

// Zrušíme session a vyčistíme všetky údaje
session_destroy();
$_SESSION = array();

// Presmerujeme na prihlasovaciu stránku
header('Location: login.php');
die();
```

**Vysvetlenie kľúčových príkazov:**
- `session_destroy()` - úplne zruší session a všetky uložené dáta
- `$_SESSION = array()` - vyčistí pole $_SESSION pre istotu
- `header('Location: login.php')` - presmeruje na prihlasovaciu stránku
- `die()` - ukončí vykonávanie skriptu

### Registrácia (register.php)

Proces registrácie nového používateľa:

```php
// Spracovanie odoslaného registračného formulára
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	// Čistíme vstupy od nebezpečného kódu
	$user_form = filter_var(htmlspecialchars($_POST['user']), FILTER_SANITIZE_STRING);
	$password_form = filter_var(htmlspecialchars($_POST['password']), FILTER_SANITIZE_STRING);
	$password2_form = filter_var(htmlspecialchars($_POST['password2']), FILTER_SANITIZE_STRING);
	
	// Kontroly vstupov, všetky polia musia byť vyplnené
	if (empty($user_form) || empty($password_form) || empty($password2_form)) {
		$errors = '<li>Please fill in all the required fields.</li>';
	} else {
		// Pripojíme sa k databáze
		include 'db/functions.php';
		$database = new Database();
		$connection = $database->connection();

		// Overíme, či užívateľské meno už náhodou neexistuje
		$statement = $connection->prepare('SELECT * FROM users WHERE user = :user LIMIT 1');
		$statement->execute(array(':user' => $user_form));	
		$result = $statement->fetch();
	
		// Ak fetch vráti niečo iné ako false, užívateľ už existuje
		if ($result != false) {
			$errors .= '<li>Sorry, the username already exists.</li>';
		}

		// Zahashujeme heslo - nikdy neukladáme heslo v čistom texte!
		$password_form = hash('sha512', $password_form);
		$password2_form = hash('sha512', $password2_form);

		// Overíme, či sa heslá zhodujú
		if ($password_form != $password2_form) {
			$errors .= '<li>The password confirmation does not match.</li>';
		}
	}

	// Ak nemáme žiadne chyby, vytvoríme nového užívateľa
	if ($errors == '') {
		$statement = $connection->prepare('INSERT INTO users (id_user, user, password) VALUES (null, :user, :password)');
		$statement->execute(array(
					':user' => $user_form,
				':password' => $password_form
			));
		// Presmerovanie na prihlasovaciu stránku
		
		header('Location: login.php');
	}
}
```

**Vysvetlenie kľúčových príkazov:**
- `empty($user_form)` - kontrola, či je premenná prázdna
- `SELECT * FROM users WHERE user = :user LIMIT 1` - vyhľadanie užívateľa v databáze s použitím pomenovaných parametrov (`:user`)
- `$statement->execute(array(':user' => $user_form))` - použitie pomenovaných parametrov v PDO
- `$result = $statement->fetch()` - načítanie jedného riadku výsledku
- `$result != false` - kontrola, či bol nájdený užívateľ (ak áno, meno už existuje)
- `hash('sha512', $password_form)` - vytvorenie hash-u pre bezpečné uloženie hesla
- `INSERT INTO users (id_user, user, password) VALUES (null, :user, :password)` - vloženie nového používateľa
- `null` v SQL príkaze - použitie auto-increment hodnoty pre id_user

## Štruktúra súborov

Projekt je rozdelený do niekoľkých hlavných súborov a adresárov:

### Hlavné PHP súbory

- **index.php** - vstupný bod aplikácie, presmeruje na ďalšie stránky podľa stavu prihlásenia
- **login.php** - prihlasovacia stránka s formulárom a spracovaním prihlásenia
- **register.php** - registračná stránka pre nových používateľov
- **logout.php** - odhlásenie používateľa a zrušenie session
- **projects.php** - hlavná stránka pre správu projektov a úloh (CRUD operácie)
- **today.php** - zobrazenie úloh na aktuálny deň
- **calendar.php** a **calendar2.php** - zobrazenie a správa kalendára udalostí
- **delete.php** - spracovanie mazania záznamov
- **content.php** - hlavný obsah aplikácie po prihlásení
- **main.php** - hlavná stránka pre neprihlásených používateľov

### Adresárová štruktúra

- **db/** - súbory súvisiace s databázou
  - **functions.php** - trieda pre pripojenie k databáze
  - **kanban.sql** - SQL skript pre vytvorenie databázy
- **views/** - šablóny pre zobrazenie stránok
- **parts/** - časti šablón, ktoré sa môžu opakovať na viacerých stránkach
- **js/** - JavaScript súbory
- **css/** - CSS štýly
- **img/** - obrázky
- **events/** - súbory súvisiace s udalosťami kalendára

## Podrobný popis funkcií a príkazov

### SQL_CALC_FOUND_ROWS

```php
$projects = $connection->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM projects WHERE id_user = ? ORDER BY id_project DESC");
```

Táto MySQL funkcia počíta celkový počet riadkov, ktoré by sa vrátili bez limitov LIMIT. Používa sa na implementáciu stránkovania. Po tomto dopyte môžete získať celkový počet riadkov pomocou `SELECT FOUND_ROWS()`.

### strtotime() a práca s dátumami

```php
$start_date = filter_var(htmlspecialchars($_POST['start_date']), FILTER_SANITIZE_STRING); 
$start_date = date("Y-m-d", strtotime($start_date));
```

- `strtotime($date)` - konvertuje textový popis dátumu na UNIX timestamp (počet sekúnd od 1.1.1970)
- `date("Y-m-d", timestamp)` - formátuje timestamp na string vo formáte rok-mesiac-deň

### Práca s PDO (PHP Data Objects)

```php
$statement = $connection->prepare('INSERT INTO projects (id_user, project_name) VALUES (?, ?)');
$statement->execute(array($id_user, $project_name));
$add_project = $statement->fetch();
```

**Vysvetlenie:**
- PDO je rozhranie na prístup k databáze, ktoré podporuje prepared statements
- `prepare()` pripraví SQL príkaz s parametrami (otázniky)
- `execute()` vykoná príkaz, pričom nahradí parametre skutočnými hodnotami
- `fetch()` získa jeden riadok výsledku
- `fetchAll()` získa všetky riadky výsledku
- `rowCount()` vráti počet ovplyvnených/vrátených riadkov

### Pretypovanie v PHP

```php
$id_user = filter_var(htmlspecialchars($_POST['id_user']), FILTER_SANITIZE_STRING);
$id_user = (int)$id_user; 
```

- `(int)$value` - explicitné pretypovanie na integer (celočíselný typ)
- `(string)$value` - explicitné pretypovanie na string (reťazec)
- `(float)$value` alebo `(double)$value` - explicitné pretypovanie na desatinné číslo
- `(bool)$value` alebo `(boolean)$value` - explicitné pretypovanie na boolean (true/false)

### HTTP stavové kódy

```php
http_response_code(400);
```

Nastavuje HTTP stavový kód odpovede servera. Kódy:
- 200: OK (úspešná požiadavka)
- 400: Bad Request (chybná požiadavka, napr. chýbajúce údaje)
- 401: Unauthorized (neautorizovaný prístup)
- 403: Forbidden (zakázaný prístup)
- 404: Not Found (zdroj nebol nájdený)
- 500: Internal Server Error (vnútorná chyba servera)

### Superglobálne premenné

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_form = $_POST['user'];
}
```

- `$_SERVER` - informácie o serveri a prostredí
  - `$_SERVER['REQUEST_METHOD']` - metóda požiadavky (GET, POST, ...)
  - `$_SERVER['HTTP_USER_AGENT']` - informácie o prehliadači
  - `$_SERVER['REMOTE_ADDR']` - IP adresa klienta
- `$_POST` - dáta odoslané metódou POST
- `$_GET` - dáta odoslané metódou GET
- `$_SESSION` - session premenné
- `$_COOKIE` - cookies
- `$_FILES` - nahraté súbory

### Kontrola existencie a prázdnosti

```php
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    // Kód sa vykoná, ak $_SESSION['user'] existuje a nie je prázdny
}
```

- `isset($var)` - skontroluje, či premenná existuje a nie je NULL
- `empty($var)` - skontroluje, či je premenná prázdna (NULL, 0, '0', '', array(), false)
- `!empty($var)` - skontroluje, či premenná nie je prázdna
- `is_null($var)` - skontroluje, či je premenná NULL

### Pomenované parametre v PDO

V projekte sa používajú dva rôzne spôsoby používania parametrov v PDO:

#### 1. Otázniky (pozičné parametre)

```php
$statement = $connection->prepare("SELECT * FROM projects WHERE id_user = ?");
$statement->execute(array($_SESSION['id_user']));
```

Pri tomto spôsobe sú parametre nahrádzané hodnotami v poli podľa ich poradia.

#### 2. Pomenované parametre (:názov)

```php
$statement = $connection->prepare('SELECT * FROM users WHERE user = :user LIMIT 1');
$statement->execute(array(':user' => $user_form));
```

Pri tomto spôsobe sú parametre označené názvom (`:user`) a hodnoty sú priradené pomocou asociatívneho poľa.

### Operátory v PHP

V projektových súboroch sa používajú rôzne operátory:

- `=` - priradenie hodnoty premennej
- `==` - porovnanie hodnôt (bez kontroly typu)
- `===` - porovnanie hodnôt aj typov
- `!=` - nerovná sa (hodnota)
- `!==` - nerovná sa (hodnota alebo typ)
- `<` a `>` - menší a väčší než
- `<=` a `>=` - menší alebo rovný, väčší alebo rovný
- `&&` alebo `and` - logické AND (a zároveň)
- `||` alebo `or` - logické OR (alebo)
- `!` - logické NOT (negácia)
- `.` - operátor spájania reťazcov
- `.=` - operátor pripojenia k reťazcu
- `+` a `-` - sčítanie a odčítanie
- `*` a `/` - násobenie a delenie
- `%` - modulo (zvyšok po delení)
- `++` a `--` - inkrementácia a dekrementácia

### Podmienky a cykly

#### Podmienky

```php
// If-else podmienka
if (podmienka) {
    // kód sa vykoná, ak je podmienka splnená
} else {
    // kód sa vykoná, ak podmienka nie je splnená
}

// If-elseif-else podmienka
if (podmienka1) {
    // kód sa vykoná, ak je podmienka1 splnená
} elseif (podmienka2) {
    // kód sa vykoná, ak podmienka1 nie je splnená, ale podmienka2 áno
} else {
    // kód sa vykoná, ak žiadna z podmienok nie je splnená
}

// Ternárny operátor (skrátená podmienka)
$premenna = (podmienka) ? 'hodnota_ak_true' : 'hodnota_ak_false';
```

#### Cykly

```php
// While cyklus - vykonáva sa, kým je podmienka splnená
while ($id = $statement->fetch(PDO::FETCH_ASSOC)) {
    // kód sa vykoná pre každý riadok výsledku
}

// For cyklus - presný počet opakovaní
for ($i = 0; $i < 10; $i++) {
    // kód sa vykoná 10-krát
}

// Foreach cyklus - pre každý prvok v poli
foreach ($projects as $project) {
    // kód sa vykoná pre každý projekt v poli $projects
}
```

### Explode a Implode

Funkcie `explode()` a `implode()` slúžia na prácu s reťazcami a poľami:

```php
// Explode - rozdelenie reťazca na pole podľa oddeľovača
$reťazec = "jablko,hruška,banán";
$pole = explode(",", $reťazec);
// $pole teraz obsahuje ["jablko", "hruška", "banán"]

// Implode - spojenie poľa do reťazca
$pole = ["jablko", "hruška", "banán"];
$reťazec = implode(", ", $pole);
// $reťazec teraz obsahuje "jablko, hruška, banán"
```

### Ukončenie skriptu

```php
// Ukončenie skriptu s chybovou správou
die("Chybová správa");

// Ukončenie skriptu bez správy (často po presmerovaní)
die();

// Alternatívny spôsob ukončenia
exit();
```

### Požiadavka include a require

```php
// Include - vloží obsah súboru, ak súbor neexistuje, zobrazí sa warning
include 'db/functions.php';

// Require - vloží obsah súboru, ak súbor neexistuje, vyhodí fatálnu chybu
require 'views/login.view.php';

// Include_once a require_once - zabezpečia, že súbor bude vložený iba raz
include_once 'file.php';
require_once 'file.php';
```

## Webové servery a protokoly

### Čo je to web server?
Webový server je aplikácia, ktorá je zodpovedná za obsluhu dopytov prostredníctvom protokolu HTTP. Spracováva požiadavky od klientov (webových prehliadačov) a zasiela im odpovede – zvyčajne vo forme HTML stránok, obrázkov, skriptov alebo iných typov súborov.

### Aké webové servery poznáme?
- **Apache HTTP Server** - najrozšírenejší webový server, open-source
- **Nginx** - vysokovýkonný webový server a reverzná proxy
- **Microsoft IIS** (Internet Information Services) - webový server od spoločnosti Microsoft
- **LiteSpeed** - komerčná alternatíva k Apache s vyšším výkonom
- **Caddy** - moderný webový server s automatickou podporou HTTPS

### Základné nastavenia Apache Web Serveru
- **httpd.conf** alebo **apache2.conf** - hlavný konfiguračný súbor
- **DocumentRoot** - adresár, kde sa nachádzajú webové súbory
- **ServerName** - definuje hostiteľské meno servera
- **Listen** - definuje IP adresu a port, na ktorom server počúva
- **VirtualHost** - konfigurácia pre hosťovanie viacerých webových stránok na jednom serveri
- **Directory** - nastavenia prístupu k adresárom

### Aké lokálne vývojové prostredia poznáme?
- **XAMPP** - krížová platforma (X), Apache, MariaDB/MySQL, PHP, Perl
- **WAMP** - Windows, Apache, MySQL, PHP
- **MAMP** - Mac, Apache, MySQL, PHP
- **LAMP** - Linux, Apache, MySQL, PHP
- **Laragon** - moderné vývojové prostredie pre Windows
- **Docker** - kontajnerizačná platforma pre vytváranie izolovaných prostredí

### Rozdiel medzi lokálnym vývojovým prostredím a webovým serverom
Lokálne vývojové prostredie je aplikácia, ktorej úlohou je nainštalovať a nakonfigurovať aplikačné komponenty ako sú web server alebo databázový server na príslušný operačný systém. Môže obsahovať základné administračné nástroje na ich spúšťanie atď.

Webový server je aplikácia, ktorá je zodpovedná za obsluhu dopytov prostredníctvom protokolu HTTP.

### Je Apache HTTP server a XAMPP to isté?
URČITE NIE!!! Apache HTTP Server je samostatný webový server, zatiaľ čo XAMPP je balík softvéru, ktorý obsahuje Apache HTTP Server, MariaDB/MySQL, PHP a Perl. XAMPP poskytuje konfiguráciu všetkých týchto komponentov ako lokálne vývojové prostredie.

### Aké alternatívy Apache HTTP Server poznáme?
- Nginx
- Microsoft IIS
- LiteSpeed
- Caddy
- Node.js (s Express.js)
- Tomcat (pre Java aplikácie)

### Aké alternatívy XAMPP poznáme?
- WAMP
- MAMP
- LAMP
- Laragon
- Vagrant
- Docker

### Čo znamená direktíva VirtualHost *:443?
Direktíva `VirtualHost *:443` v konfigurácii Apache znamená, že daný virtuálny host bude počúvať na porte 443 (štandardný port pre HTTPS) na všetkých dostupných IP adresách servera (označené hviezdičkou). Táto konfigurácia sa používa pre webové stránky, ktoré používajú zabezpečený protokol HTTPS.

### Čo znamená direktíva Listen 127.0.0.1:80?
Direktíva `Listen 127.0.0.1:80` v Apache konfigurácii definuje, že server bude počúvať na porte 80 (štandardný HTTP port), ale len na lokálnej slučke (localhost, 127.0.0.1). To znamená, že webový server bude dostupný len zo samotného počítača, na ktorom beží, a nie z externých zariadení v sieti.

### Aké najrozšírenejšie protokoly poznáme?
- **HTTP** (Hypertext Transfer Protocol) - protokol pre prenos webových stránok
- **HTTPS** (HTTP Secure) - zabezpečená verzia HTTP
- **FTP** (File Transfer Protocol) - protokol pre prenos súborov
- **SMTP** (Simple Mail Transfer Protocol) - protokol pre odosielanie emailov
- **POP3/IMAP** - protokoly pre prijímanie emailov
- **DNS** (Domain Name System) - protokol pre preklad doménových mien na IP adresy
- **SSH** (Secure Shell) - zabezpečený protokol pre vzdialený prístup k zariadeniam
- **WebSocket** - protokol pre obojsmernú komunikáciu v reálnom čase

### Čo je to URL?
URL (Uniform Resource Locator) je adresa, ktorá špecifikuje umiestnenie zdroja na internete. Skladá sa z:
- schémy protokolu (http://, https://, ftp://)
- domény alebo IP adresy
- portu (voliteľný)
- cesty k zdroju
- parametrov (voliteľné)
- fragmentu (voliteľný)

Príklad: `https://www.example.com:443/path/to/page.html?parameter=value#section`

### URI, URL, URN
- **URI** (Uniform Resource Identifier) - všeobecný identifikátor zdroja na internete
- **URL** (Uniform Resource Locator) - špecifický typ URI, ktorý poskytuje prostriedky na prístup k zdroju
- **URN** (Uniform Resource Name) - identifikuje zdroj pomocou jedinečného názvu v danom mennom priestore, bez špecifikácie umiestnenia alebo spôsobu prístupu

### Čo presne je HTTP?
HTTP (Hypertext Transfer Protocol) je aplikačný protokol pre distribuované, kolaboratívne, hypermediálne informačné systémy. Je základom dátovej komunikácie na World Wide Web. HTTP funguje ako protokol typu požiadavka-odpoveď v modeli klient-server.

### HTTP metódy:
- **GET** - žiada o dáta zo špecifikovaného zdroja (nemení dáta na serveri)
- **POST** - posiela dáta na server (môže vytvoriť nový záznam)
- **PUT** - aktualizuje celý existujúci záznam
- **PATCH** - aktualizuje časť existujúceho záznamu
- **DELETE** - maže špecifikovaný záznam
- **HEAD** - rovnaké ako GET, ale vracia len hlavičky (nie obsah)
- **OPTIONS** - vracia zoznam HTTP metód, ktoré server podporuje
- **TRACE** - poskytuje diagnostické informácie
- **CONNECT** - vytvorí tunel k serveru 

## Git - Verziovací systém

### Čo je Git a ako funguje?
Git je distribuovaný systém na správu verzií, ktorý umožňuje vývojárom sledovať zmeny v zdrojovom kóde počas vývoja softvéru. Ponúka efektívnu spoluprácu medzi vývojármi a pomáha organizovať vývoj projektu.

### Základné prvky Gitu

#### Repozitár (Repository)
Repozitár je miesto, kde Git ukladá všetky verzie projektu a metadáta. Existujú dva typy repozitárov:
- **Lokálny repozitár** - nachádza sa na počítači vývojára
- **Vzdialený repozitár** - nachádza sa na serveri (napr. GitHub, GitLab, Bitbucket)

#### Commit
Commit predstavuje konkrétny bod v histórii projektu. Je to záznam o zmenách v súboroch, ktorý obsahuje:
- Jednoznačný identifikátor (hash)
- Commit správu popisujúcu zmeny
- Autora a čas vykonania
- Odkaz na predchádzajúci commit (rodič)

Commity vytvárajú postupnosť (históriu) zmien v projekte.

#### Branch (Vetva)
Branch je samostatná vývojová línia v rámci repozitára. Umožňuje pracovať na rôznych funkciách alebo opravách paralelne, bez vzájomného ovplyvňovania.

#### Merge (Zlúčenie)
Merge je proces zlúčenia zmien z jednej vetvy do druhej. Pri zlúčení sa zmeny z vybranej vetvy integrujú do cieľovej vetvy.

### Typy vetiev a ich účel

#### Master/Main
Hlavná vetva projektu. Obsahuje najnovšiu stabilnú verziu kódu. Do nej robíme merge ostatných vetiev. V novších repozitároch sa často používa názov "main" namiesto "master".

#### Develop
Vetva vývoja. Používa sa na vývoj nových funkcií a opráv chýb. Z tejto vetvy sa zvyčajne vytvárajú feature vetvy a do nej sa zlučujú späť po dokončení.

#### Feature
Vetva funkcie. Vytvára sa pre vývoj špecifickej funkcie. Tieto vetvy sa zvyčajne vytvárajú z vetvy develop a po dokončení sa do nej zase zlučujú.

#### Hotfix
Vetva opravy chyby. Vytvára sa na opravu kritickej chyby v existujúcej verzii kódu. Vytvára sa zvyčajne z master/main vetvy a po oprave sa zlučuje späť do master/main aj develop vetvy.

#### Release
Vetva vydania. Vytvára sa pre vydanie novej verzie projektu. Obsahuje stabilný kód, ktorý sa chystá zverejniť. Po dokončení testovania sa zlučuje do master/main a develop vetvy.

### Pull Request
Pull request (PR) je žiadosť o zlúčenie zmien z jednej vetvy do druhej, zvyčajne na vzdialených repozitároch ako GitHub, GitLab alebo Bitbucket. Pull request umožňuje:
- Preskúmanie kódu (code review) pred zlúčením
- Diskusiu o navrhovaných zmenách
- Automatizované testovanie kódu
- Dokumentáciu zmien a ich zámerov

## Rozdiel medzi include a require v PHP

### Include
- Ak súbor, ktorý sa má zahrnúť, neexistuje, zobrazí sa varovanie (E_WARNING), ale PHP skript bude pokračovať vo vykonávaní
- Vloženie sa vykonáva v čase interpretácie, t.j. kód sa vloží do aktuálneho súboru pred jeho spustením
- Používa sa na zahrnutie voliteľných súborov, ktoré nie sú nevyhnutné pre fungovanie skriptu

Príklad:
```php
include 'volitelny_subor.php';
// Kód pokračuje ďalej, aj keď súbor neexistuje
```

### Require
- Ak súbor, ktorý sa má zahrnúť, neexistuje, zobrazí sa fatálna chyba (E_COMPILE_ERROR) a PHP skript sa zastaví
- Vloženie sa vykonáva v čase kompilovania, t.j. kód sa skontroluje pred spustením skriptu
- Používa sa na zahrnutie povinných súborov, ktoré sú nevyhnutné pre fungovanie skriptu

Príklad:
```php
require 'databazove_funkcie.php';
// Ak súbor neexistuje, skript sa ukončí s fatálnou chybou
```

### Include_once a Require_once
Varianty `include_once` a `require_once` majú rovnaké vlastnosti ako ich základné verzie, ale zabezpečujú, že súbor bude vložený iba raz, aj keď je požiadavka na vloženie volaná viackrát.

Príklad:
```php
require_once 'config.php';
// Súbor sa vloží iba raz, aj keď táto požiadavka volaná viackrát
```

Toto je užitočné na zabránenie redefinícii funkcií a tried, čo by mohlo spôsobiť chyby. 

## XAMPP - podrobnejšie vysvetlenie

### Význam skratky XAMPP
- **X** - cross-platform, funguje na rôznych operačných systémoch
- **A** - Apache - konkrétny typ webového servera, vyvinutý nadáciou Apache, cez ktorý beží PHP skript
- **M** - MariaDB - je komunitou vyvinutý, komerčne podporovaný systém správy relačných databáz MySQL
- **P** - PHP - skriptovací jazyk na strane servera
- **P** - Perl - je rodina skriptovacích programovacích jazykov, ktoré sú syntaxou podobné jazyku C (nie je potrebné ovládať)

## Lokálny web development a jeho súčasti

### Lokálny web development
Lokálny web development je proces vývoja webových aplikácií na vlastnom počítači, bez potreby pripojenia k vzdialenému serveru. Umožňuje vývojárom testovať a debugovať svoje aplikácie v izolovanom prostredí pred ich nasadením na produkčný server.

#### Výhody lokálneho vývoja
- **Rýchlosť** - zmeny sa prejavujú okamžite, bez potreby nahrávania na vzdialený server
- **Offline práca** - možnosť pracovať bez pripojenia k internetu
- **Bezpečnosť** - testovanie bez rizika narušenia produkčných dát
- **Flexibilita** - možnosť experimentovať s rôznymi konfiguráciami
- **Nižšie náklady** - nie je potrebné platiť za hosting počas vývoja

### Komponenty XAMPP a ich funkcie

XAMPP je populárne lokálne vývojové prostredie, ktoré integruje niekoľko kľúčových komponentov:

#### Apache HTTP Server
- Spracováva HTTP požiadavky a slúži ako webový server
- Zodpovedá za spracovanie URL adries a smerovanie požiadaviek na správne súbory
- Využíva modul mod_php na spracovanie PHP skriptov
- Konfigurovateľný pomocou súborov httpd.conf a .htaccess
- Podporuje virtuálne hosty (viacero webových stránok na jednom serveri)

#### MariaDB/MySQL
- Databázový server na ukladanie a správu dát
- Poskytuje relačnú databázu pre webové aplikácie
- Komunikuje s PHP skriptami cez špeciálne funkcie alebo PDO
- Spravovaný pomocou príkazového riadku alebo phpMyAdmin

#### PHP
- Skriptovací jazyk na strane servera
- Vykonáva sa na serveri a generuje HTML, ktorý sa posiela klientovi
- V XAMPP je PHP integrovaný s Apache pomocou modulu mod_php
- Konfigurovateľný pomocou php.ini súboru
- Podporuje prístup k databáze, manipuláciu so súbormi a mnoho ďalších funkcií

#### Perl
- Skriptovací jazyk používaný pre systémovú administráciu a webové aplikácie
- V XAMPP je zahrnutý, ale zriedkavo používaný v porovnaní s PHP
- Môže byť použitý cez CGI alebo mod_perl

#### phpMyAdmin
- Webové rozhranie pre správu MySQL/MariaDB databáz
- Umožňuje vytvárať, upravovať a mazať databázy a tabuľky
- Poskytuje používateľsky prívetivé rozhranie pre SQL príkazy
- Umožňuje import a export dát v rôznych formátoch

### Apache HTTP Server a skriptovacie jazyky

Apache HTTP Server môže pracovať s rôznymi skriptovacími jazykmi prostredníctvom rôznych mechanizmov:

#### PHP
- **mod_php** - modul Apachu, ktorý priamo integruje PHP interpreter do webového servera
- Každý PHP súbor je spracovaný týmto modulom a výstup je poslaný klientovi
- Rýchle a efektívne pre väčšinu webových aplikácií

#### Python
- **mod_wsgi** - modul pre integráciu Python aplikácií s Apache
- **CGI/FastCGI** - staršie mechanizmy pre spracovanie Python skriptov
- Python web frameworky ako Django, Flask môžu byť hostované na Apache

#### Ruby
- **mod_ruby** - modul pre Ruby skripty
- **Passenger** - modul pre Ruby on Rails aplikácie
- **CGI/FastCGI** - alternatívne metódy spustenia Ruby skriptov

#### Perl
- **mod_perl** - integruje Perl interpreter do Apache
- **CGI** - tradičný spôsob spúšťania Perl skriptov

#### Node.js
- Zvyčajne beží ako samostatný server
- Môže byť prepojený s Apache pomocou reverznej proxy
- Apache presmeruje požiadavky na Node.js server, ktorý ich spracuje

### Alternatívy lokálneho vývojového prostredia

#### WAMP/MAMP/LAMP
- Podobné ako XAMPP, ale špecifické pre konkrétne operačné systémy (Windows/Mac/Linux)
- Poskytujú podobnú sadu nástrojov: Apache, MySQL, PHP

#### Laragon
- Moderné vývojové prostredie pre Windows
- Automaticky konfiguruje virtuálne hosty
- Jednoduchšie prepínanie medzi verziami PHP
- Integrovaný terminál a správca databáz

#### Docker
- Kontajnerizačná platforma umožňujúca vytvorenie izolovaných prostredí
- Každá služba (webový server, databáza) je v samostatnom kontajneri
- Poskytuje konzistentné prostredie naprieč vývojovým a produkčným prostredím
- Docker Compose umožňuje definovať a spúšťať multi-kontajnerové aplikácie

#### Local by Flywheel
- Navrhnutý špeciálne pre WordPress vývoj
- Jednoduchá správa viacerých lokalít
- Automatická konfigurácia HTTPS

#### Node.js + Express
- Alternatíva založená na JavaScript
- Vhodná pre vývoj jednostránkových aplikácií (SPA)
- Kombinuje sa s MongoDB namiesto SQL databáz

### Spojenie medzi PHP a MySQL/MariaDB

PHP poskytuje niekoľko spôsobov na pripojenie k MySQL alebo MariaDB databázam:

#### Mysqli Extension
- Novšia verzia rozšírenia pre MySQL, špecifická pre MySQL/MariaDB
- Podporuje procedurálny aj objektovo orientovaný prístup
- Poskytuje lepšiu bezpečnosť a výkon v porovnaní so starším mysql rozšírením

```php
<?php
// Objektový prístup
$mysqli = new mysqli("localhost", "username", "password", "database");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Procedurálny prístup
$connection = mysqli_connect("localhost", "username", "password", "database");
if (!$connection) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>
```

#### PDO (PHP Data Objects)
- Univerzálne rozhranie pre prístup k rôznym databázam (nie len MySQL)
- Objektovo orientované API
- Poskytuje pokročilé funkcie ako prepared statements a transaction control
- Jednoduchý prechod medzi rôznymi typmi databáz

```php
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=database", "username", "password");
    // Nastavenie režimu chýb na výnimky
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
```

### Výber dát z databázy

#### Základný SELECT dotaz
```php
<?php
// Použitie PDO
$stmt = $pdo->prepare("SELECT * FROM users WHERE status = ?");
$stmt->execute(["active"]);
$users = $stmt->fetchAll();

// Alebo s mysqli
$stmt = $mysqli->prepare("SELECT * FROM users WHERE status = ?");
$status = "active";
$stmt->bind_param("s", $status); // "s" znamená string
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
?>
```

#### Výber jedného záznamu
```php
<?php
// Použitie PDO
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Alebo s mysqli
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $id); // "i" znamená integer
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
```

#### Počítanie záznamov
```php
<?php
// Použitie PDO
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE status = ?");
$stmt->execute(["active"]);
$count = $stmt->fetchColumn();

// Alebo s mysqli
$stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE status = ?");
$status = "active";
$stmt->bind_param("s", $status);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
?>
```

### Modifikácia dát v databáze

#### Vloženie nového záznamu (INSERT)
```php
<?php
// Použitie PDO
$stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->execute([$username, $email, $password]);
$lastId = $pdo->lastInsertId();

// Alebo s mysqli
$stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $email, $password);
$stmt->execute();
$lastId = $mysqli->insert_id;
?>
```

#### Aktualizácia záznamu (UPDATE)
```php
<?php
// Použitie PDO
$stmt = $pdo->prepare("UPDATE users SET email = ?, status = ? WHERE id = ?");
$stmt->execute([$email, $status, $id]);
$affectedRows = $stmt->rowCount();

// Alebo s mysqli
$stmt = $mysqli->prepare("UPDATE users SET email = ?, status = ? WHERE id = ?");
$stmt->bind_param("ssi", $email, $status, $id);
$stmt->execute();
$affectedRows = $stmt->affected_rows;
?>
```

#### Odstránenie záznamu (DELETE)
```php
<?php
// Použitie PDO
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);
$affectedRows = $stmt->rowCount();

// Alebo s mysqli
$stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$affectedRows = $stmt->affected_rows;
?>
```

#### Transakcie
Transakcie umožňujú zoskupiť viacero operácií do jedného atomického celku - buď sa vykonajú všetky, alebo žiadna.

```php
<?php
// Použitie PDO
try {
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
    $stmt->execute([$amount, $fromAccount]);
    
    $stmt = $pdo->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$amount, $toAccount]);
    
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    echo "Transaction failed: " . $e->getMessage();
}

// Alebo s mysqli
$mysqli->begin_transaction();
try {
    $stmt = $mysqli->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $fromAccount);
    $stmt->execute();
    
    $stmt = $mysqli->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
    $stmt->bind_param("di", $amount, $toAccount);
    $stmt->execute();
    
    $mysqli->commit();
} catch (Exception $e) {
    $mysqli->rollback();
    echo "Transaction failed: " . $e->getMessage();
}
?>
``` 

## Základná PHP syntax

- Kód píšeme vždy medzi tag `<?php ..... ?>`
- Pri uvedení bloku kódu do týchto značiek, PHP kód bude po spustení analyzovať stránku a hľadať tieto otváracie a zatváracie značky, kým nenájde začiatočnú – potom uvidí PHP kód
- Používajte bodkočiarky na ukončenie príkazu - bodkočiarky sú to, čo nášmu kódu povie, že toto je hotový príkaz
- `echo` slúži pre output (ako print v Pythone), neuzatvárame ho však do zátvoriek, ale do úvodzoviek
- Ukončujúci tag `?>` vkladá bodkočiarku a nie je potrebná pre posledný riadok v bloku (pre začiatok ju všade dávajte, aby ste sa naučili syntax)
- Ak máme kód zostavený iba z PHP kódu, nemusíme písať ukončujúci tag `?>`

Príklad základnej PHP syntax:

```php
<?php
    // Toto je komentár
    echo "Hello, World!";      // Vypíše text do prehliadača
    
    $premenna = "text";        // Vytvorenie premennej
    echo $premenna;            // Vypísanie obsahu premennej
    
    // Podmienka if-else
    if ($premenna == "text") {
        echo "Premenná obsahuje text";
    } else {
        echo "Premenná neobsahuje text";
    }
?>
```

## Základy objektovo orientovaného programovania (OOP) v PHP

### Čo je to OOP
- OOP je spôsob, ako písať kód.
- Snaží sa brať si príklad z reálneho sveta, kde každá vec má nejaké vlastnosti a schopnosti.
- V PHP je OOP o triedach (class), podľa ktorých sa vytvárajú objekty.
- Pre každú vec z reálneho sveta si vyrobíme triedu (class) a tá nám bude hovoriť, aké vlastnosti (premenné) a schopnosti (funkcie) má každý objekt, ktorý sa podľa tejto triedy vyrobí.

#### Príklad triedy
Napríklad našou vecou môže byť banner:
- Naša trieda by bola `class Banner { }`
- Banner by mal vlastnosť `$heading`
- Schopnosti bannera by mohli byť napríklad nastaviť nadpis `function set_heading()`

```php
<?php
class Banner {
    // Vlastnosť (property)
    public $heading;
    
    // Metóda (method)
    public function set_heading($text) {
        $this->heading = $text;
    }
    
    public function display() {
        echo "<div class='banner'><h1>{$this->heading}</h1></div>";
    }
}

// Vytvorenie objektu
$hlavny_banner = new Banner();
$hlavny_banner->set_heading("Vitajte na našej stránke");
$hlavny_banner->display();
?>
```

### Magické metódy v PHP
- Metódy, ktoré sú automaticky vykonané.
- Začínajú s dvoma podtržníkmi `__`
- Poznáme `__construct()`, `__destruct()`, `__call()`, `__callStatic()`, `__get()`, `__set()`, `__isset()`, `__unset()`, `__sleep()`, `__wakeup()`, `__invoke()`

#### __construct()
- Slúži na vytváranie objektov z tried.
- Je volaná automaticky pri vytvorení nového objektu pomocou `new`
- Napríklad by sme mohli chcieť iné menu v Headeri a iné vo Footeri. Vytvorili by sme si triedu Menu, ktorá by dokázala vytvoriť objekty Header_menu a Footer_menu, pričom každé menu by zobrazovalo iné stránky.

```php
<?php
class Menu {
    // Vlastnosť
    public $menu;
    
    // Konštruktor
    public function __construct($type) {
        if ($type == "header") {
            $this->menu = ["Domov", "O nás", "Kontakt"];
        } else if ($type == "footer") {
            $this->menu = ["Kontakt", "Obchodné podmienky", "Ochrana súkromia"];
        }
    }
    
    // Schopnosť
    public function get_menu() {
        return $this->menu;
    }
}

// Vytvorenie objektov
$Header_menu = new Menu("header");
$Footer_menu = new Menu("footer");

// Výpis menu
echo "Header menu: " . implode(", ", $Header_menu->get_menu());
echo "<br>";
echo "Footer menu: " . implode(", ", $Footer_menu->get_menu());
?>
```

#### __call()
- Magická metóda `__call()` je volaná vtedy, keď sa snažíme zavolať funkciu ktorá neexistuje
- Jej parametrami sú metóda (prvý parameter) a parametre (druhý parameter ako pole)

```php
<?php
class Logger {
    public function __call($name, $arguments) {
        echo "Pokúšate sa volať neexistujúcu metódu: $name<br>";
        echo "S parametrami: " . implode(', ', $arguments);
    }
    
    public function log($message) {
        echo "Záznam: $message";
    }
}

$logger = new Logger();
$logger->log("Normálna správa"); // Volá existujúcu metódu
$logger->debug("Toto je debug správa"); // Volá __call(), pretože debug() neexistuje
?>
``` 

## PHP superglobálne premenné

PHP poskytuje niekoľko predefinovaných (superglobálnych) premenných, ktoré sú dostupné vo všetkých častiach skriptu. Tieto premenné sú asociatívne polia, ktoré obsahujú rôzne druhy informácií:

### $GLOBALS
- Uchováva všetky používateľom definované globálne premenné
- Názvy globálnych premenných fungujú ako kľúče k ich hodnotám
- Umožňuje prístup k globálnym premenným z akéhokoľvek miesta v skripte, vrátane funkcií a metód

```php
<?php
$x = 10;
function test() {
    echo $GLOBALS['x']; // Prístup ku globálnej premennej $x
}
test(); // Vypíše 10
?>
```

### $_SERVER
- Obsahuje údaje o hlavičkách, skriptoch, cestách a umiestnení skriptov
- Poskytuje všetky informácie o prostredí, na ktorom beží náš PHP skript
- Tieto informácie si vieme vytiahnuť pomocou definovaných premenných

Najčastejšie používané prvky $_SERVER:
- `$_SERVER['REQUEST_METHOD']` - Metóda použitá na prístup k stránke (GET, POST, PUT, atď.)
- `$_SERVER['PHP_SELF']` - Názov aktuálne vykonávaného skriptu
- `$_SERVER['REQUEST_URI']` - URI, ktorý bol poskytnutý pre prístup k tejto stránke
- `$_SERVER['HTTP_USER_AGENT']` - Informácie o prehliadači používateľa
- `$_SERVER['REMOTE_ADDR']` - IP adresa používateľa
- `$_SERVER['SERVER_NAME']` - Názov serveru, na ktorom beží skript

### $_POST
- Ukladá vstupné údaje odoslané formulárom pomocou metódy POST
- Tieto údaje sú dostupné v asociatívnom poli
- Používa sa pre prenos citlivých údajov, pretože dáta nie sú viditeľné v URL

```php
<?php
// Formulár odoslaný s poľom input s name="username"
if(isset($_POST['username'])) {
    echo "Prihlásený používateľ: " . $_POST['username'];
}
?>
```

### $_GET
- Ukladá vstupné údaje odoslané v URL pomocou metódy GET
- Tieto údaje sú dostupné v asociatívnom poli
- Vhodné pre nekritické dáta ako vyhľadávacie parametre

```php
<?php
// URL: example.php?id=123&page=2
echo "ID: " . $_GET['id']; // Vypíše 123
echo "Strana: " . $_GET['page']; // Vypíše 2
?>
```

### $_COOKIES
- Uchováva zadávanie údajov prostredníctvom súborov cookie HTTP
- Kľúče tohto poľa sú definované pri nastavení cookies
- Umožňuje prístup k hodnotám cookies nastaveným v prehliadači používateľa

```php
<?php
// Nastavenie cookie
setcookie("user", "John", time() + 3600);

// Neskôr v kóde alebo pri ďalšom načítaní stránky
if(isset($_COOKIE['user'])) {
    echo "Používateľ: " . $_COOKIE['user']; // Vypíše "Používateľ: John"
}
?>
```

### $_REQUEST
- Ukladá vstupné údaje vo forme HTTP POST, GET a cookies
- Obsahuje údaje odoslaného formulára a všetky údaje súborov cookie
- $_REQUEST je pole obsahujúce údaje z $_GET, $_POST a $_COOKIE
- Používa sa, ak chceme pristupovať k dátam bez ohľadu na metódu odoslania

```php
<?php
// Funguje pre dáta odoslané cez GET aj POST
$username = $_REQUEST['username'];
?>
```

### $_SESSION
- Ukladá premenné relácie (session variables)
- Tieto premenné sú dostupné na všetkých stránkach v rámci jednej relácie
- Relácia trvá, kým je používateľ prihlásený alebo kým sa relácia neukončí
- Pred použitím je potrebné zavolať session_start()

```php
<?php
session_start();
// Uloženie premennej do session
$_SESSION['username'] = "John";

// Na inej stránke po session_start():
echo "Prihlásený používateľ: " . $_SESSION['username'];
?>
```

### $_ENV
- Obsahuje informácie o prostredí (environment), v ktorom PHP beží
- Tieto informácie zahŕňajú napr. nastavenia servera, cesty k súborom, premenné prostredia
- Vieme sa k nim dostať pomocou preddefinovaných premenných

```php
<?php
// Zobraziť cestu k PHP
echo $_ENV['PATH'];
?>
```

### $_FILES
- Obsahuje informácie o súboroch nahraných na server pomocou metódy POST
- Táto premenná je dvojrozmerné asociatívne pole
- Kľúč je názov HTML input poľa typu "file", v ktorom sa súbor nahráva
- Hodnota je asociatívne pole obsahujúce informácie o nahranom súbore pre daný kľúč
- Premenná je dostupná len vtedy, ak bol formulár odoslaný metódou POST
- Ak sa vyskytne chyba pri nahrávaní súboru, kód chyby bude 0

```php
<?php
if(isset($_FILES['upload'])) {
    // Informácie o nahranom súbore
    echo "Názov súboru: " . $_FILES['upload']['name'] . "<br>";
    echo "Typ súboru: " . $_FILES['upload']['type'] . "<br>";
    echo "Veľkosť súboru: " . $_FILES['upload']['size'] . " bytov<br>";
    echo "Dočasný súbor: " . $_FILES['upload']['tmp_name'] . "<br>";
    
    // Presun nahraného súboru do cieľového adresára
    move_uploaded_file($_FILES['upload']['tmp_name'], "uploads/" . $_FILES['upload']['name']);
}
?>
```

## Dynamické načítanie CSS súborov

Funkcia `getCSS()` je príkladom pokročilého použitia superglobálnych premenných a práce s JSON v PHP. Táto funkcia dynamicky načíta a vloží do hlavičky HTML príslušné CSS súbory na základe názvu aktuálnej stránky:

```php
<?php
function getCSS() {
    // Načítanie a dekódovanie JSON súboru
    $jsonStr = file_get_contents("data/datas.json");
    $data = json_decode($jsonStr, true);
    
    // Získanie názvu aktuálnej stránky z URL
    $stranka = basename($_SERVER['REQUEST_URI']);
    $stranka = explode(".", $stranka)[0]; // Odstránenie prípony .php
    
    // Získanie zoznamu CSS súborov pre danú stránku
    $suboryCSS = $data["stranky"][$stranka];
    
    // Vloženie CSS súborov do HTML
    foreach($suboryCSS as $subor) {
        echo '<link rel="stylesheet" href="css/' . $subor . '.css">';
    }
}
?>
```

### Vysvetlenie funkcie getCSS():

1. `file_get_contents("data/datas.json")` - načíta obsah JSON súboru ako reťazec
2. `json_decode($jsonStr, true)` - dekóduje JSON reťazec na asociatívne pole PHP
3. `basename($_SERVER['REQUEST_URI'])` - získa názov súboru z aktuálnej URL cesty
4. `explode(".", $stranka)[0]` - rozdelí názov súboru podľa bodky a vezme prvú časť (odstráni príponu)
5. `$data["stranky"][$stranka]` - získa pole CSS súborov pre aktuálnu stránku z asociatívneho poľa
6. Cyklus `foreach` potom prejde cez všetky CSS súbory v zozname a vytvorí HTML tagy `<link>` na ich načítanie

Príklad JSON súboru (data/datas.json):
```json
{
    "stranky": {
        "index": ["main", "header", "footer"],
        "contact": ["main", "contact", "footer"],
        "about": ["main", "about", "footer"]
    }
}
```

Táto funkcia umožňuje centralizovane spravovať, ktoré CSS súbory sa majú načítať pre jednotlivé stránky, bez nutnosti manuálne upravovať hlavičku každej stránky.

## HTTP komunikácia

HTTP komunikácia prebieha medzi klientom a serverom pomocou požiadaviek (request) a odpovedí (response).

### HTTP požiadavka (request)

Klient odosiela serveru požiadavku, ktorá obsahuje informácie o tom, akú akciu chce vykonať. Požiadavka obsahuje:

- **Metódu**: Určuje typ akcie, ktorú chce klient vykonať (GET, POST, PUT, DELETE)
- **URL**: Určuje zdroj, ktorého sa požiadavka týka
- **Hlavičku**: Obsahuje dodatočné informácie o požiadavke, ako napríklad typ klienta, preferovaný jazyk, cookies
- **Telo**: Voliteľná časť, ktorá obsahuje dáta, ktoré sa posielajú serveru (napr. pri formulári)

### HTTP odpoveď (response)

Údaj, ktorý server vydá ako odpoveď na konkrétnu požiadavku, sa nazýva HTTP odpoveď. Tá pozostáva zo:

- **Status kódu**: Číselný kód, ktorý informuje o stave požiadavky (200 OK, 404 Not Found, 500 Internal Server Error)
- **Hlavičky**: Obsahujú dodatočné informácie o odpovedi, ako napríklad typ obsahu, dĺžka obsahu, cookies
- **Tela**: Obsahuje dáta, ktoré server posiela klientovi (napr. HTML stránka, JSON objekt)

### HTTP stavové kódy

#### Informačné (1xx)
Tieto kódy oznamujú, že prebieha dočasná akcia. Klient by mal požiadavku zopakovať.

- **100 – Continue** – server informuje klienta, že akceptuje požiadavku a klient môže ďalej posielať dáta
- **102 – Processing** - server spracováva požiadavku klienta a čoskoro odošle odpoveď
- **103 – Early hints** - server posiela klientovi predbežné informácie o odpovedi

#### Úspech (2xx)
Tieto kódy oznamujú, že požiadavka bola úspešne spracovaná, server odoslal klientovi naspäť požadovaný obsah.

- **200 – OK** – požiadavka bola úspešná a obsah je k dispozícii
- **201 – Created** - server vytvoril nový zdroj v reakcii na požiadavku klienta. Používa sa, keď klient požiada o vytvorenie nového objektu, napríklad súboru alebo záznamu v databáze
- **202 – Accepted** - server prijal požiadavku klienta, ale jej spracovanie ešte nie je dokončené
- **204 – No Content** - server úspešne spracoval požiadavku klienta, ale nemá žiadnu odpoveď. Používa sa vtedy, keď klient požiada napr. o vymazanie objektu

#### Presmerovanie (3xx)
Tieto kódy oznamujú, že klient by mal presmerovať svoju požiadavku na inú URL adresu, resp. urobiť ďalšiu akciu pre získanie obsahu.

- **301 – Moved Permanently** – požadovaný zdroj bol natrvalo presunutý na inú URL adresu a klient by mal aktualizovať svoju URL
- **303 – See Other** – klient by mal znova odoslať požiadavku na inú URL adresu, ale metóda požiadavky by sa mala zmeniť (napr. pri formulári zmeníme metódu GET na POST)
- **304 – Not Modified** – požadovaný zdroj sa od poslednej požiadavky klienta nezmenil. Klient môže použiť lokálne uloženú kópiu zdroja

#### Chybný klient (4xx)
Tieto kódy oznamujú, že klient urobil chybu v požiadavke.

- **400 – Bad Request** – požiadavka klienta je nesprávne naformátovaná a mal by požiadavku opraviť a znova odoslať (nesprávne ID produktu, neplatná URL)
- **401 – Unauthorized** – klient nie je autorizovaný na prístup k požadovanému zdroju (mal by použiť správny login a heslo)
- **402 – Payment Required** – klient musí zaplatiť za prístup k požadovanému zdroju
- **403 – Forbidden** – klient nemá prístup k požadovanému zdroju. Klient by mal požiadavku zrušiť
- **409 – Conflict** – server nemôže splniť požiadavku klienta kvôli konfliktu s aktuálnym stavom požadovaného zdroja (klient vytvára už existujúci účet)

#### Chyba servera (5xx)
Tieto kódy oznamujú, že na serveri sa vyskytla chyba.

- **503 – Service Unavailable** – server je dočasne nedostupný z dôvodu preťaženia alebo údržby
- **504 – Gateway Timeout** – webserver, ktorý funguje ako brána alebo proxy server, nedostal včasnú odpoveď od nadriadeného servera (upstream server), ktorý je potrebný na spracovanie požiadavky klienta
- **510 – Not Extended** – server nerozumie požiadavke klienta, pretože obsahuje hlavičku rozšírenia, ktorú server nepodporuje

## Pokročilé koncepty objektovo orientovaného programovania v PHP

### Objekty z tried
- Každá trieda potrebuje objekty, aby sme ich mohli používať
- Z triedy môžeme vytvoriť viacero objektov. Každý objekt má svoje vlastné hodnoty vlastností a vie vykonávať metódy definované v triede
- Objekty vedia interagovať medzi sebou a s ostatnými časťami programu
- Vytvárame ich vždy pomocou slova `new` – nový objekt, ktorý je inštanciou konkrétnej triedy

### Kľúčové slovo $this
- Špeciálne kľúčové slovo, ktoré odkazuje na aktuálny objekt, v ktorom sa nachádza
- Používa sa na prístup k vlastnostiam a metódam aktuálneho objektu
- Využitie spočíva v tom, že vlastnosti a metódy sú definované v rámci triedy, ale prístup k nim sa vykonáva z konkrétnych objektov, vytvorených z tejto triedy
- Pomáha nám odlíšiť vlastnosti a metódy aktuálneho objektu od premenných a funkcií definovaných mimo triedy

### Modifikátory prístupu
Vlastnosti a metódy môžu mať modifikátory prístupu, ktoré riadia, kde k nim možno pristupovať. Existujú tri modifikátory prístupu:

- **Public** – k vlastnosti alebo metóde sa dá pristupovať odkiaľkoľvek. Predvolene sa dá pristupovať ku každej premennej
- **Protected** – k vlastnosti alebo metóde je možné pristupovať v rámci triedy a tried odvodených z tejto triedy
- **Private** - vlastnosť alebo metóda sú prístupné IBA v rámci triedy

### Magické metódy

- **__construct()**: Konštruktor - volá sa pri vytvorení nového objektu
- **__destruct()**: Deštruktor sa volá pri zničení objektu
- **__get()**: Volá sa pri prístupe k vlastnosti, ktorá neexistuje
- **__set()**: Volá sa pri nastavení vlastnosti, ktorá neexistuje
- **__call()**: Volá sa pri volaní metódy, ktorá neexistuje
- **__isset()**: Volá sa pri testovaní, či vlastnosť existuje
- **__unset()**: Volá sa pri zničení vlastnosti
- **__toString()**: Volá sa pri konverzii objektu na reťazec
- **__invoke()**: Umožňuje volanie objektu ako funkcie
- **__clone()**: Používa sa na klonovanie objektu. Vytvárame si teda kópiu existujúceho objektu

#### Konštruktor
- Konštruktor je špeciálna metóda v triede, ktorá sa automaticky volá pri vytvorení nového objektu z tejto triedy. Môžeme si ho predstaviť ako "inštalačný program", ktorý inicializuje a konfiguruje novovytvorený objekt
- Konštruktor nemá návratový typ
- Názov konštruktora je vždy zhodný s názvom triedy
- V triede môže existovať aj viacero konštruktorov, v PHP je však iba jeden

#### Deštruktor
- Špeciálna metóda, ktorá sa automaticky volá pri zničení objektu
- Hlavný účel deštruktora je uvoľniť zdroje, ktoré objekt alokoval počas svojej existencie. To zahŕňa zatvorenie súborov, uvoľnenie pamäte alebo ukončenie sieťových pripojení
- Používame pri práci so súbormi a pri práci s databázami (databázové spojenie)

#### __get() a __set()
- **__get()** sa automaticky volá pri pokuse o prístup k neprístupnej alebo neexistujúcej vlastnosti objektu
- Umožňuje dynamické zachytávanie a spracovávanie prístupu k vlastnostiam
- Príklady použitia: implementáciu virtuálnych vlastností, poskytnutie prístupu k interným dátam s kontrolou, dynamické načítanie hodnôt z databázy

- **__set()** sa automaticky volá pri pokuse o priradenie hodnoty neprístupnej alebo neexistujúcej vlastnosti objektu
- Umožňuje dynamické zachytávanie a spracovávanie priradenia vlastnosti
- Príklady použitia: implementáciu virtuálnych vlastností, kontrolu a validáciu priraďovaných hodnôt, dynamické ukladanie hodnôt do databázy

#### __call()
- Táto metóda sa volá, keď sa programátor pokúsi zavolať metódu, ktorá neexistuje
- Umožňuje nám tak definovať vlastné správanie pre volanie neexistujúcich metód
- Používa sa pre univerzálne spracovanie rôznych typov požiadaviek
- Zjednodušuje kód a znižuje duplicitu

#### __clone()
- Používa sa na klonovanie objektu. To znamená, že nový objekt bude mať rovnaké vlastnosti a hodnoty ako pôvodný objekt, ale bude existovať samostatne a akékoľvek zmeny vykonané na kópii neovplyvnia pôvodný objekt
- V klonovaní rozlišujeme medzi plytkou a hlbokou kópiou
- **Plytká kópia (Shallow copy)** – bez metódy __clone(), skopírujú len referencie na vlastnosti, nie ich hodnoty. To znamená, že úpravy vlastností v klonovanom objekte by sa prejavili aj v pôvodnom objekte
- **Hlboká kópia (Deep copy)** – Metóda __clone() umožňuje vytvoriť hlbokú kópiu objektu, kde sa skopírujú aj hodnoty vlastností, nie len referencie. To znamená, že úpravy vlastností v klonovanom objekte sa neprejavia v pôvodnom objekte

### Rozdiel medzi premennými, vlastnosťami, funkciami a metódami

- **Premenná (Variable)**: je definovaná v rámci funkcie alebo bloku kódu. Premenné sú ako dočasné nádoby na uloženie dát
- **Vlastnosť (Property)**: je definovaná v rámci triedy. Vlastnosti sú ako ingrediencie pizze - definujú, z čoho sa objekt skladá
- **Funkcia**: Samostatný blok kódu, ktorý vykonáva určitú úlohu. Funkcie nemusia súvisieť s žiadnou konkrétnou triedou
- **Metóda**: Funkcia definovaná v rámci triedy. Metódy sú ako kroky v návode na pizzu - definujú, čo objekt dokáže robiť (alebo ako bude chutiť)

### Menné priestory (Namespaces)

Menné priestory sú kvalifikátory, ktoré riešia niekoľko problémov:

- Umožňujú lepšiu organizáciu zoskupením tried, ktoré spolupracujú pri plnení úlohy
- Zlepšujú prehľadnosť kódu
- Umožňujú použiť rovnaký názov pre viac ako jednu triedu: 
  - Môžeme mať napríklad sadu tried, ktoré popisujú tabuľku HTML, ako napríklad Table, Row, a Cell
  - Môžeme však mať aj inú sadu tried na popis nábytku, ako napríklad Table, Chair a Bed
  - Priestory názvov možno použiť na usporiadanie tried do dvoch rôznych skupín a zároveň zabrániť zámene dvoch tried Table a Table -> použili by sme napríklad html\Table a furniture\Table

## Vzťahy medzi súbormi a ich funkcionalita

### Základná štruktúra projektu

Projekt používa MVC-like architektúru (Model-View-Controller), kde sú súbory rozdelené podľa ich úlohy:

#### Kontroléry (Controllers)
Hlavné PHP súbory v koreňovom adresári, ktoré spracúvajú logiku, získavajú dáta a načítavajú príslušné šablóny (views).

#### Šablóny (Views)
Súbory v adresári `views/` obsahujú HTML kód spolu s PHP pre zobrazenie dát používateľovi.

#### Model
Databázové funkcie v adresári `db/` zabezpečujú prístup k dátam.

### Kľúčové vzťahy medzi súbormi

#### Kontroléry a ich šablóny

1. **Hlavná stránka**
   - `index.php` - kontroluje prihlásenie a presmerováva používateľa
   - `main.php` - kontrolér pre úvodnú stránku (neprihlásení používatelia)
   - `views/main.view.php` - šablóna úvodnej stránky

2. **Autentifikácia**
   - `login.php` - spracúva prihlásenie používateľa
   - `views/login.view.php` - šablóna prihlasovacej stránky
   - `register.php` - spracúva registráciu používateľa
   - `views/register.view.php` - šablóna registračnej stránky
   - `logout.php` - odhlásenie používateľa a zrušenie session

3. **Dashboard**
   - `content.php` - kontrolér pre dashboard (hlavný obsah po prihlásení)
   - `views/content.view.php` - šablóna dashboardu s navigačnými kartami

4. **Kalendár**
   - `calendar.php` - kontrolér pre kalendár (získava udalosti z databázy)
   - `views/calendar.view.php` - šablóna kalendára (HTML štruktúra)
   - `calendar2.php` - obsahuje JavaScript konfiguráciu FullCalendar-a a spracovanie dát
   
   **Vzťah medzi calendar.php a calendar2.php**: 
   - `calendar.php` získava dáta z databázy a volá `calendar.view.php`
   - `calendar.view.php` na konci importuje `calendar2.php`
   - `calendar2.php` transformuje dáta získané v `calendar.php` do JSON formátu a inicializuje kalendár

5. **Projekty a úlohy**
   - `projects.php` - kontrolér pre správu projektov a úloh
   - `views/projects.view.php` - šablóna pre zobrazenie projektov a úloh
   - `delete.php` - univerzálny kontrolér pre mazanie záznamov

6. **Denný prehľad**
   - `today.php` - kontrolér pre zobrazenie aktuálnych úloh
   - `views/today.view.php` - šablóna pre denný prehľad úloh

#### Zdieľané komponenty

1. **Hlavička a päta**
   - `parts/header.php` - obsahuje HTML hlavičku, meta tagy, CSS, navigáciu
   - `parts/footer.php` - obsahuje pätu stránky a spoločné JavaScripty

2. **Modálne okná**
   - `events/modals/modalAdd.php` - modálne okno pre pridávanie udalostí
   - `events/modals/modalEdit.php` - modálne okno pre úpravu udalostí
   - `events/modals/newProject.php` - modálne okno pre nový projekt
   - `events/modals/newTask.php` - modálne okno pre novú úlohu

3. **Akcie a operácie**
   - `events/actions/*.php` - súbory obsahujúce spracovanie AJAX požiadaviek

### Workflow - tok údajov v aplikácii

#### Prihlásenie používateľa
1. Používateľ navštívi `login.php`
2. `login.php` načíta šablónu `views/login.view.php`
3. Po odoslaní formulára `login.php` overí používateľa v databáze
4. Po úspešnom prihlásení je používateľ presmerovaný na `index.php`, ktorý ho ďalej presmeruje na `content.php`

#### Zobrazenie kalendára
1. Používateľ klikne na kartu "CALENDAR" na dashboarde, čo ho presmeruje na `calendar.php`
2. `calendar.php` získa udalosti z databázy pre prihláseného používateľa
3. `calendar.php` načíta šablónu `views/calendar.view.php`
4. Na konci `calendar.view.php` sa importuje `calendar2.php`
5. `calendar2.php` spracuje dáta do JSON formátu a inicializuje FullCalendar
6. Používateľ môže interagovať s kalendárom - pridávať, upravovať a mazať udalosti

#### Správa projektov a úloh
1. Používateľ klikne na kartu "PROJECTS" na dashboarde, čo ho presmeruje na `projects.php`
2. `projects.php` získa zoznam projektov a úloh z databázy
3. `projects.php` načíta šablónu `views/projects.view.php`
4. Šablóna zobrazí Kanban tabuľu s projektmi a úlohami
5. Používateľ môže pridávať, upravovať a mazať projekty a úlohy
6. Pri pridávaní a úprave sa zobrazujú modálne okná z adresára `events/modals/`

### Databázové prepojenia

Všetky súbory, ktoré pracujú s databázou, importujú triedu Database z `db/functions.php`:

```php
require_once('db/functions.php');
$database = new Database();
$connection = $database->connection();
```

Táto trieda poskytuje metódu `connection()`, ktorá vytvára a vracia PDO pripojenie k MySQL databáze.

