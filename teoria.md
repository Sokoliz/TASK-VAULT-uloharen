# Teoretická dokumentácia projektu TASK VAULT

Tento dokument vysvetľuje teoretické koncepty a technologické princípy použité v projekte TASK VAULT.

## Obsah

1. [MVC Architektúra](#mvc-architektúra)
2. [Objektovo-orientované programovanie (OOP)](#objektovo-orientované-programovanie-oop)
3. [Štýl komentovania kódu](#štýl-komentovania-kódu)
4. [Composer a správa závislostí](#composer-a-správa-závislostí)
5. [Autoloading tried](#autoloading-tried)
6. [Práca s databázou pomocou PDO](#práca-s-databázou-pomocou-pdo)
7. [URL Routing](#url-routing)
8. [Session Management](#session-management)
9. [Cache pre statické súbory](#cache-pre-statické-súbory)
10. [Štruktúra priečinkov](#štruktúra-priečinkov)

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
- Podadresáre: `page/`, `parts/`, `Project/`, `Today/`
- Príklady: `login.view.php`, `register.view.php`, `calendar.view.php`, `projects.view.php`
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

Dedičnosť je použitá na vytváranie špecializovaných tried, ktoré zdieľajú vlastnosti a metódy so svojimi rodičovskými triedami. Napríklad všetky view triedy dedia od základnej triedy `View`.

### Polymorfizmus

Umožňuje rôznym triedam implementovať rovnaké metódy rôznymi spôsobmi.

### Návrhové vzory

- **Singleton** - použitý v triede `Database` pre zabezpečenie jedného pripojenia k databáze
- **Front Controller** - všetky požiadavky prechádzajú cez hlavný `index.php` súbor

---

## Štýl komentovania kódu

V projekte sme implementovali špecifický štýl komentovania, ktorý zlepšuje čitateľnosť a zrozumiteľnosť kódu pre slovensky hovoriacich vývojárov.

### Slovenské konverzačné komentáre

Namiesto tradičných PHP docblock komentárov používame jednoriadkové slovenské komentáre v konverzačnom štýle:

#### Pôvodný docblock štýl:

```php
/**
 * ProjectsView class
 *
 * Object-oriented wrapper for the projects view
 */
class ProjectsView
{
    /**
     * Data to be passed to the view
     * @var array
     */
    private $viewData;
}
```

#### Nový konverzačný štýl:

```php
// Trieda ProjectsView - obaľuje pohľad pre projekty
// používa OOP prístup aby sa to krajšie integrovalo do zvyšku kódu
class ProjectsView
{
    // Dáta, ktoré posunieme do pohľadu - taká malá krabička s vecami
    private $viewData;
}
```

### Výhody konverzačného štýlu

1. **Lepšia zrozumiteľnosť** - komentáre vysvetľujú účel kódu prirodzeným jazykom
2. **Lokalizácia** - slovenský jazyk robí kód prístupnejším pre domácich vývojárov
3. **Menej formálnosti** - ľahšie pochopiteľné než tradičné technické dokumentačné komentáre
4. **Kontext** - komentáre často poskytujú nielen čo kód robí, ale aj prečo to robí
5. **Prívetivosť pre začiatočníkov** - nižšia vstupná bariéra pre pochopenie kódu

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

### Router trieda

V projekte je implementovaná trieda `Router` v `app/Core/Router.php`, ktorá umožňuje definovať a spracovávať rôzne URL cesty:

```php
// V index.php
$router = new Router();

// Definujeme cesty - každá URL má priradenú triedu a metódu, ktorá sa zavolá
$router->addRoutes([
    // Základná cesta - domovská stránka
    '/' => [IndexViewController::class, 'processRequest'],

    // Autentifikačné cesty - prihlásenie, registrácia, odhlásenie
    '/register' => [AuthController::class, 'register'],
    '/login' => [AuthController::class, 'login'],
    '/logout' => [AuthController::class, 'logout'],

    // ďalšie cesty...
]);

// Spustíme spracovanie požiadavky
$router->dispatch();
```

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

## Cache pre statické súbory

V projekte je implementovaný systém pre cachovanie statických súborov, čo výrazne zlepšuje výkon aplikácie.

### Implementácia v index.php

```php
// Optimalizácia - cachovanie pre statické súbory
$cssCache = 31536000; // 1 rok v sekundách
$jsCache = 31536000;  // 1 rok v sekundách
$imgCache = 31536000; // 1 rok v sekundách

$requestUri = $_SERVER['REQUEST_URI'];
$fileExtension = pathinfo($requestUri, PATHINFO_EXTENSION);

// Nastav cache headery pre statické súbory
if (in_array($fileExtension, ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'woff', 'woff2', 'ttf'])) {
    $filePath = __DIR__ . $requestUri;
    if (file_exists($filePath)) {
        switch ($fileExtension) {
            case 'css':
                header('Content-Type: text/css');
                header('Cache-Control: public, max-age=' . $cssCache);
                break;
            // ďalšie typy súborov...
        }

        // Použi ETag pre optimalizáciu
        $etag = md5_file($filePath);
        header("ETag: \"$etag\"");

        if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == "\"$etag\"") {
            // Súbor sa nezmenil, klient má aktuálnu verziu
            header("HTTP/1.1 304 Not Modified");
            exit;
        }

        // Výstup statického súboru
        readfile($filePath);
        exit;
    }
}
```

### Výhody cachovania

1. **Rýchlejšie načítavanie stránok** - prehliadač nesťahuje opakovane statické súbory
2. **Zníženie záťaže servera** - menej požiadaviek na server
3. **Lepšia používateľská skúsenosť** - stránky sa načítavajú rýchlejšie
4. **Úspora šírky pásma** - menej dát prenesených medzi serverom a klientom

### Kombinované súbory

Okrem štandardného cachovania projekt používa aj kombinovanie CSS a JavaScript súborov:

```php
// Príklad z Header.php
if ($this->useCombinedFiles) {
    // Use the combined file with a version parameter for cache busting
    $version = filemtime(__DIR__ . '/../../../public/css/style.css') .
              filemtime(__DIR__ . '/../../../public/css/dynamic-theme.css');
    $html .= '<link rel="stylesheet" href="/public/css/combine.php?v=' . $version . '">' . PHP_EOL;
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
    - **page/** - Hlavné pohľady stránok
    - **parts/** - Časti pohľadov (hlavička, pätička)
    - **Project/** - Špecializované pohľady pre projekty
    - **Today/** - Špecializované pohľady pre denný prehľad
  - **Core/** - Základné triedy a funkcie aplikácie (Database, Session, Router)
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

1.  Controllers
    Úloha: Spracovávajú požiadavky používateľa (napr. kliknutie na odkaz, odoslanie formulára).
    Čo robia: Prijímajú vstupy, komunikujú s modelmi, vyberajú správny pohľad (view) a posielajú mu dáta.
    Príklad: UserController.php môže mať metódy ako login(), register(), logout().

2.  Models
    Úloha: Pracujú s dátami a databázou.
    Čo robia: Obsahujú logiku pre získavanie, ukladanie, úpravu a mazanie dát.
    Príklad: User.php model môže mať metódy ako findById($id), save(), delete().

3.  Views
    Úloha: Zobrazujú dáta používateľovi.
    Čo robia: Sú to šablóny (často HTML s PHP), ktoré zobrazujú informácie, ktoré im pošle controller.
    Príklad: login.php, dashboard.php – HTML stránky, ktoré zobrazujú formuláre alebo tabuľky.

4.  Core
    Úloha: Obsahuje základné triedy a knižnice, ktoré využíva celý projekt.
    Čo robia: Môže tu byť napríklad router (na smerovanie požiadaviek), základný controller, základný model, atď.
    Príklad: Core/Controller.php, Core/Model.php, Core/Router.php.

5.  Config
    Úloha: Nastavenia projektu.
    Čo robia: Obsahuje konfiguračné súbory, napríklad pripojenie k databáze, nastavenia aplikácie, atď.
    Príklad: config.php, database.php.

6.  index.php
    Úloha: Vstupný bod aplikácie (tzv. front controller).
    Čo robí:
    Načíta základné nastavenia a knižnice (autoload, config).
    Spustí router, ktorý rozhodne, ktorý controller a akú metódu zavolať na základe URL.
    Typicky vyzerá takto:
    Apply to Calendar.php
    require_once 'config/config.php';
    require_once 'core/Router.php';
    // ... ďalšie require/autoload

        $router = new Router();
        $router->route($_SERVER['REQUEST_URI']);

Zhrnutie: index.php je miesto, kde sa všetko začína – príde požiadavka, index.php ju spracuje a odovzdá ďalej do systému.
Ako to celé funguje spolu?
Používateľ zadá URL (napr. /user/login).
index.php prijme požiadavku a spustí router (z Core).
Router rozhodne, že má zavolať UserController a jeho metódu login().
UserController môže zavolať metódy z User modelu (Models), ak treba pracovať s databázou.
Controller potom vyberie správny view (napr. login.php) a pošle mu dáta.
View zobrazí výsledok používateľovi.
