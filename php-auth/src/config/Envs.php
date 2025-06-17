<?php

namespace app\config;

use Dotenv\Dotenv;
use Exception;

// require __DIR__ . '/../../vendor/autoload.php';

class Envs
{
    private static bool $loaded = false;

    private static function load(): void
    {
        if (self::$loaded) return;

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        self::$loaded = true;
    }

    public static function get(string $key): string
    {
        self::load();

        if (!isset($_ENV[$key])) {
            throw new Exception("Missing environment variable: $key");
        }

        return $_ENV[$key];
    }

    public static function getInt(string $key): int
    {
        return intval(self::get($key));
    }
}
