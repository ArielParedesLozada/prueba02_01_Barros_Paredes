<?php

namespace app\config;

use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

// require __DIR__ . '/../../vendor/autoload.php';

class BycryptAdapter
{
    private static PasswordHasherInterface $hasher;

    // Inicializa el hasher si no está ya creado
    private static function init(): void
    {
        if (!isset(self::$hasher)) {
            // NativePasswordHasher usa bcrypt por defecto
            self::$hasher = new NativePasswordHasher();
        }
    }

    public static function hash(string $password): string
    {
        self::init();
        return self::$hasher->hash($password);
    }

    public static function compare(string $plain, string $hashed): bool
    {
        self::init();
        return self::$hasher->verify($hashed, $plain);
    }
}
