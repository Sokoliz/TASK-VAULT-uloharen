# Teoretická dokumentácia projektu TASK VAULT

Tento dokument vysvetľuje teoretické koncepty a technologické princípy použité v projekte TASK VAULT.

## Obsah

1. [MVC Architektúra](#mvc-architektúra)
2. [Objektovo-orientované programovanie (OOP)](#objektovo-orientované-programovanie-oop)
3. [Composer a správa závislostí](#composer-a-správa-závislostí)
4. [Autoloading tried](#autoloading-tried)
5. [Práca s databázou pomocou PDO](#práca-s-databázou-pomocou-pdo)
6. [URL Routing](#url-routing)
7. [Session Management](#session-management)
8. [Štruktúra priečinkov](#štruktúra-priečinkov)

---

## MVC Architektúra

TASK VAULT je postavený na architektonickom vzore Model-View-Controller (MVC), ktorý rozdeľuje aplikáciu na tri hlavné komponenty:

### Model

- Reprezentuje dáta a logiku aplikácie
- V projekte sa nachádza v priečinku `app/Models/`
- Príklady: `User.php`, `Project.php`, `Task.php`, `Calendar.php`
- Modely komunikujú priamo s databázou a poskytujú metódy pre prácu s dátami

### View (Pohľad)

- Zobrazuje dáta používateľovi a poskytuje používateľské rozhranie
- V projekte sa nachádza v priečinku `app/Views/`
- Príklady: `login.php`, `register.php`, `calendar.php`, `projects.php`
- Pohľady obsahujú minimálnu logiku, väčšinou len zobrazovanie dát

### Controller (Kontrolér)

- Spracováva používateľské vstupy a koordinuje modely a pohľady
- V projekte sa nachádza v priečinku `app/Controllers/`
- Príklady: `AuthController.php`, `ProjectController.php`, `TaskController.php`
- Kontroléry prijímajú požiadavky z `index.php`, spracúvajú ich pomocou modelov a následne zobrazujú pohľady

### Výhody MVC v projekte

1. **Oddelenie záujmov** - kód je lepšie organizovaný
2. **Znovupoužiteľnosť** - komponenty môžu byť použité na viacerých miestach
3. **Paralelný vývoj** - rôzne časti aplikácie môžu byť vyvíjané nezávisle
4. **Lepšia udržiavateľnosť** - zmeny v jednej časti nespôsobia problémy v iných častiach

---

## Objektovo-orientované programovanie (OOP)

Projekt TASK VAULT využíva objektovo-orientované programovanie (OOP) na dosiahnutie lepšej organizácie a udržiavateľnosti kódu.

### Triedy a objekty

Aplikácia používa triedy na reprezentáciu entít ako používatelia, projekty, úlohy a udalosti kalendára. Napríklad:

```php
// Príklad triedy Project
class Project
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Metódy pre prácu s projektami
    public function getAllByUser($userId)
    {
        // kód pre získanie projektov používateľa
    }

    // ďalšie metódy...
}
```

### Zapuzdrenie (Encapsulation)

Triedy skrývajú svoju internú implementáciu a poskytujú len verejné rozhranie pre interakciu. Napríklad trieda `Database` zapuzdruje všetky detaily pripojenia k databáze.

### Dedičnosť (Inheritance)

Dedičnosť je použitá na vytváranie špecializovaných tried, ktoré zdieľajú vlastnosti a metódy so svojimi rodičovskými triedami.

### Polymorfizmus

Umožňuje rôznym triedam implementovať rovnaké metódy rôznymi spôsobmi.

### Návrhové vzory

- **Singleton** - použitý v triede `Database` pre zabezpečenie jedného pripojenia k databáze
- **Front Controller** - všetky požiadavky prechádzajú cez hlavný `index.php` súbor

---

## Composer a správa závislostí

[Composer](https://getcomposer.org/) je nástroj na správu závislostí pre PHP, ktorý umožňuje deklarovať a spravovať knižnice, od ktorých projekt závisí.

### Composer v projekte TASK VAULT

1. **composer.json** - hlavný konfiguračný súbor Composeru:

```json
{
  "name": "marek/productivity",
  "authors": [
    {
      "name": "marek",
      "email": "marek.sokol@student.ukf.sk"
    }
  ],
  "require": {},
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      "Config\\": "config/",
      "Public\\": "public/"
    }
  }
}
```

2. **Autoloading** - Composer generuje autoloader, ktorý sa používa na automatické načítanie tried

3. **Inštalácia závislostí** - Aj keď projekt momentálne nevyužíva externé knižnice (`"require": {}`), v budúcnosti môžu byť pridané jednoducho aktualizáciou composer.json

### Výhody používania Composeru

- Automatizované načítavanie tried
- Jednoduchá správa závislostí
- Štandardizácia vývoja PHP aplikácií
- Jednoduchá integrácia externých knižníc

---

## Autoloading tried

Autoloading umožňuje automatické načítanie PHP tried bez potreby manuálneho `require` alebo `include` príkazov v každom súbore.

### PSR-4 Autoloading

TASK VAULT používa štandard PSR-4 pre autoloading, ktorý je konfigurovaný v súbore `composer.json`:

```json
"autoload": {
    "psr-4": {
        "App\\" : "app/",
        "Config\\" : "config/",
        "Public\\" : "public/"
    }
}
```

Toto nastavenie mapuje namespaces na adresáre:

- Namespace `App\` mapuje na adresár `app/`
- Namespace `Config\` mapuje na adresár `config/`
- Namespace `Public\` mapuje na adresár `public/`

### Ako autoloading funguje v praxi

1. V `index.php` sa načíta Composer autoloader:

```php
require_once __DIR__ . '/vendor/autoload.php';
```

2. Keď sa použije trieda s namespace, autoloader automaticky nájde a načíta príslušný súbor:

```php
use App\Controllers\AuthController;

$auth = new AuthController(); // Autoloader automaticky načíta app/Controllers/AuthController.php
```

3. Výhody:
   - Čistejší kód bez mnohých require/include
   - Triedy sú načítané len keď sú potrebné
   - Jednoduchšia organizácia kódu s namespacami

---

## Práca s databázou pomocou PDO

Projekt používa PHP Data Objects (PDO) na komunikáciu s databázou MySQL. PDO poskytuje abstraktnú vrstvu pre prístup k databáze.

### Singleton Database trieda

Trieda `Database` v `app/Core/Database.php` implementuje návrhový vzor Singleton, ktorý zabezpečuje, že existuje len jedno pripojenie k databáze:

```php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        $config = require __DIR__ . '/../../config/config.php';
        try {
            $this->pdo = new PDO(
                'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'],
                $config['db']['user'],
                $config['db']['pass']
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('DB Connection failed: ' . $e->getMessage());
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
```

### Používanie PDO v modeloch

Modely používajú PDO na vykonávanie SQL príkazov:

```php
// Príklad z Project.php modelu
public function getAllByUser($userId)
{
    $stmt = $this->db->prepare("SELECT * FROM projects WHERE id_user = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

### Výhody PDO

- **Bezpečnosť** - ochrana proti SQL injekciám pomocou parametrizovaných dotazov
- **Prenositeľnosť** - podpora viacerých databázových systémov
- **Objektový prístup** - lepšia integrácia s OOP kódom
- **Pokročilé funkcie** - transakcie, prepared statements atď.

---

## URL Routing

TASK VAULT používa efektívny systém smerovania na spracovanie požiadaviek používateľa.

### Front Controller Pattern

Všetky požiadavky sú spracované cez jediný vstupný bod - `index.php`. Tento prístup sa nazýva "Front Controller Pattern" a pomáha centralizovať riadenie aplikácie.

### Apache .htaccess

Pre presmerovanie všetkých požiadaviek na `index.php` sa používa súbor `.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

Tento kód zabezpečuje, že všetky požiadavky, ktoré neodkazujú na existujúci súbor alebo adresár, sú presmerované na `index.php`.

### Router v index.php

Súbor `index.php` funguje ako router, ktorý analyzuje URL a presmeruje požiadavku na príslušný kontrolér:

```php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/login':
        $auth->login();
        break;
    case '/register':
        $auth->register();
        break;
    // ďalšie cesty...
    default:
        require_once "./app/Views/index.php";
        break;
}
```

### Výhody tohto prístupu

- **Čisté URL** - bez parametrov v URL alebo .php prípon
- **Centrálne riadenie** - všetky požiadavky idú cez jeden bod
- **Flexibilita** - jednoduché pridávanie nových ciest a funkcií
- **Bezpečnosť** - lepšia kontrola nad prístupom k súborom

---

## Session Management

Správa sessions je dôležitou súčasťou webovej aplikácie. TASK VAULT implementuje vlastnú Session triedu pre efektívnu správu relácií.

### Session trieda

Trieda `Session` v `app/Core/Session.php` poskytuje metódy pre prácu s reláciami:

```php
class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }
}
```

### Používanie v kontroléroch

Kontroléry používajú Session triedu na správu prihlásenia a údajov o používateľovi:

```php
// Príklad z AuthController.php
public function login()
{
    if ($userId = $this->userModel->login($username, $password)) {
        Session::set('user_id', $userId['user_id']);
        Session::set('user', $userId['user']);
        header("Location: /content");
    }
}
```

### Kontrola prihlásenia

V mnohých kontroléroch sa používa kontrola prihlásenia na ochranu súkromných stránok:

```php
if (!Session::isLoggedIn()) {
    header("Location: /login");
    exit;
}
```

---

## Štruktúra priečinkov

TASK VAULT má jasnú a logickú štruktúru priečinkov, ktorá odráža MVC architektúru:

### Hlavné priečinky

- **app/** - Hlavný adresár aplikačného kódu
  - **Controllers/** - Kontroléry pre spracovanie požiadaviek
  - **Models/** - Modely pre prácu s databázou
  - **Views/** - Pohľady a šablóny
  - **Core/** - Základné triedy a funkcie aplikácie (Database, Session)
- **config/** - Konfiguračné súbory
  - **config.php** - Základná konfigurácia (databáza)
- **public/** - Verejne dostupné súbory
  - **css/** - Kaskádové štýly
  - **js/** - JavaScript súbory
  - **img/** - Obrázky a médiá
- **vendor/** - Knižnice spravované Composerom (generované)

### Kľúčové súbory

- **.htaccess** - Konfigurácia Apache pre smerovanie
- **index.php** - Vstupný bod aplikácie a router
- **composer.json** - Konfigurácia Composeru a autoloadingu
- **README.md** - Dokumentácia projektu

### Prečo je táto štruktúra dobrá?

1. **Modularita** - každý komponent má svoje miesto
2. **Škálovateľnosť** - jednoduché pridávanie nových funkcií
3. **Separácia záujmov** - logika, dáta a prezentácia sú oddelené
4. **Udržiavateľnosť** - jednoduchšie hľadanie a oprava chýb
5. **Štandardizácia** - podobná štruktúra ako v moderných PHP frameworkoch

---
