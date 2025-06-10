<?php
namespace App\Core;

class Session
{
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Uistíme sa, že $_SESSION je inicializované
        if (!isset($_SESSION)) {
            $_SESSION = array();
        }
    }

    public static function set($key, $value)
    {
        // Uistíme sa, že $_SESSION je inicializované
        if (!isset($_SESSION)) {
            self::start();
        }
        
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        // Uistíme sa, že $_SESSION je inicializované
        if (!isset($_SESSION)) {
            self::start();
        }
        
        return $_SESSION[$key] ?? null;
    }

    public static function destroy()
    {
        // Uistíme sa, že $_SESSION je inicializované
        if (!isset($_SESSION)) {
            self::start();
        }
        
        // Kompletne vyčistíme session
        $_SESSION = array();
        
        // Ak používame cookies, tak ich vymažeme
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Nakoniec zničíme samotnú session
        session_destroy();
    }

    public static function isLoggedIn(): bool
    {
        // Uistíme sa, že $_SESSION je inicializované
        if (!isset($_SESSION)) {
            self::start();
        }
        
        return isset($_SESSION['user_id']);
    }
}