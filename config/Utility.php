<?php
namespace App\Utility;

/**
 * Utility class
 * 
 * This class contains utility methods used throughout the application
 */
class Utility {
    /**
     * Vráti konfiguráciu obrázkov pre aplikáciu
     * 
     * @return array Konfigurácia obrázkov
     */
    public static function getImagesConfig() {
        // Načítame konfiguráciu z JSON súboru
        $configFile = __DIR__ . '/images.json';
        
        if (file_exists($configFile)) {
            $jsonConfig = file_get_contents($configFile);
            $config = json_decode($jsonConfig, true);
            
            if ($config) {
                return $config;
            }
        }
        
        // Ak sa JSON súbor nepodarí načítať, vrátime predvolené hodnoty
        return [
            'backgrounds' => [
                'main' => 'public/img/main.jpg',
                'cover' => 'public/img/cover.png'
            ],
            'icons' => [
                'logo' => 'public/img/logo1.png'
            ],
            'pages' => [
                'main' => 'public/img/main.jpg',
                'login' => 'public/img/login.jpg',
                'register' => 'public/img/register.jpg',
                'today' => 'public/img/today.jpg',
                'projects' => 'public/img/projects.jpg',
                'calendar' => 'public/img/calender.jpg'
            ]
        ];
    }

    /**
     * Formátuje dátum do čitateľného formátu
     * 
     * @param string $date Dátum vo formáte Y-m-d
     * @return string Formátovaný dátum
     */
    public static function formatDate($date) {
        $timestamp = strtotime($date);
        return date('d. m. Y', $timestamp);
    }

    /**
     * Formátuje čas do čitateľného formátu
     * 
     * @param string $time Čas vo formáte H:i:s
     * @return string Formátovaný čas
     */
    public static function formatTime($time) {
        $timestamp = strtotime($time);
        return date('H:i', $timestamp);
    }

    /**
     * Skráti text na určitý počet znakov
     * 
     * @param string $text Text na skrátenie
     * @param int $length Maximálna dĺžka
     * @param string $append Text na pridanie na koniec skráteného textu
     * @return string Skrátený text
     */
    public static function truncateText($text, $length = 100, $append = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        
        return $text . $append;
    }
} 