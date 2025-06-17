<?php

namespace app\config;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// require __DIR__ . '/../../vendor/autoload.php';

class JwtAdapter
{
    private static string $secret;

    private static function init()
    {
        self::$secret = Envs::get('JWT_SEED');
    }

    private static function parseDuration(string|int $duration): int
    {
        if (is_numeric($duration)) return (int)$duration;

        if (preg_match('/^(\d+)([smhd])$/', $duration, $matches)) {
            $value = (int)$matches[1];
            return match ($matches[2]) {
                's' => $value,
                'm' => $value * 60,
                'h' => $value * 3600,
                'd' => $value * 86400,
                default => 0,
            };
        }

        return 0; // fallback
    }

    public static function generateToken(array $payload, string|int $duration = '2h'): ?string
    {
        self::init();
        try {
            $now = time();
            $exp = $now + self::parseDuration($duration);

            $payload['iat'] = $now;
            $payload['exp'] = $exp;

            return JWT::encode($payload, self::$secret, 'HS256');
        } catch (Exception $e) {
            return null;
        }
    }

    public static function validateToken(string $token): ?array
    {
        self::init();
        try {
            $decoded = JWT::decode($token, new Key(self::$secret, 'HS256'));
            return (array) $decoded;
        } catch (Exception $e) {
            return null;
        }
    }
}
