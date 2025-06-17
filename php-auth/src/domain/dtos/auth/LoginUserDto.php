<?php

namespace app\domain\dtos\auth;

// require __DIR__ . '/../../../../vendor/autoload.php';

class LoginUserDto
{
    public string $email;
    public string $password;

    private function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public  static function create(array $object)
    {
        $email = $object['email'];
        $password = $object['password'];
        if (!$email) return ['Missing email', null];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return ['Invalid email', null];
        if (!$password) return ['Missing password', null];
        if (strlen($password) < 6) return ['Password too short', null];

        return [
            null,
            new LoginUserDto($email, $password),
        ];
    }
}
