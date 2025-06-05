<?php
namespace App\Core;

// Trieda pre správu sedení používateľov pomocou PHP sessions
class Session
{
    // Metóda pre začatie sedenia, ak ešte nie je aktívne
    public static function start()
    {
        // Kontrola, či sedenie už nie je aktívne
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Metóda pre nastavenie hodnoty v sedení podľa kľúča
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    // Metóda pre získanie hodnoty zo sedenia podľa kľúča
    // Ak kľúč neexistuje, vráti null
    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    // Metóda pre zničenie sedenia (odhlásenie používateľa)
    public static function destroy()
    {
        session_destroy();
    }

    // Metóda pre kontrolu, či je používateľ prihlásený
    // Vracia true, ak existuje user_id v sedení
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }
}