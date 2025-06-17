<?php

namespace app\domain\errors;

// require __DIR__ . '/../../../vendor/autoload.php';

use Exception;

class CustomError extends Exception
{
    public function __construct(int $code, string $message)
    {
        parent::__construct($message, $code);
    }

    public  static function badRequest(string $message)
    {
        return new CustomError(400, $message);
    }
    public  static function unauthorized(string $message)
    {
        return new CustomError(401, $message);
    }
    public  static function forbidden(string $message)
    {
        return new CustomError(403, $message);
    }
    public  static function notfound(string $message)
    {
        return new CustomError(404, $message);
    }
    public  static function internalserver(string $message = "Internal server error")
    {
        return new CustomError(500, $message);
    }
}
