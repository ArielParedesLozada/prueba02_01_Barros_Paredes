<?php

namespace app\domain\dtos\auth;

require __DIR__.'/../../../../vendor/autoload.php';

class RegisterUserDto
{
    public string $name;
    public string $email;
    public string $password;

    private function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public  static function create(array $object)
    {
        $name = $object['name'];
        $email = $object['email'];
        $password = $object['password'];

        if (!$name) return ['Missing name', null];
        if (!$email) return ['Missing email', null];
        if (!$password) return ['Missing password', null];
        if (strlen($password) < 6) return ['Password too short', null];

        return [
            null,
            new RegisterUserDto($name, $email, $password),
        ];
    }
}
