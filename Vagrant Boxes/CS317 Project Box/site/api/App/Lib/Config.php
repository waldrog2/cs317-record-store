<?php

namespace App\Lib;

class Config
{
    private static $configuration;

    public static function get_config_option($key, $default = null)
    {
        if (is_null(self::$configuration)) {
            self::$configuration = require_once(__DIR__ . '/../../config.php');
        }


        return !empty(self::$configuration[$key]) ? self::$configuration[$key] : $default; // if key exists, return it else return the default
    }
}
