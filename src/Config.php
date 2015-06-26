<?php
namespace Throup\GrabRadio;

class Config {
    public static function getLibraryPath() {
        return self::$_library;
    }

    public static function getStations() {
        return self::$_stations;
    }

    public static function setStations($array) {
        self::$_stations = (array)$array;
    }

    public static function ignoreBrands($array) {
        $array         = array_map('strtolower', $array);
        self::$_ignore = array_merge(self::$_ignore, $array);
    }

    public static function load($file = '') {
        $files = [
            '/etc/grabradio',
        ];
        if (array_key_exists('HOME', $_SERVER)) {
            $files[] = $_SERVER['HOME'] . '/.grabradiorc';
        }
        if ($file) {
            $files[] = $file;
        }
        foreach ($files as $file) {
            try {
                include_once($file);
            } catch (\Exception $e) {
                echo "Problem with config file: $file.\n";
                die($e->getMessage());
            }
        }
    }

    public static function setLibraryPath($path) {
        self::$_library = $path;
    }

    public static function toIgnore($brand) {
        return (in_array(strtolower($brand), self::$_ignore));
    }

    private static $_ignore   = [];

    private static $_library  = '';

    private static $_stations = [];
}

