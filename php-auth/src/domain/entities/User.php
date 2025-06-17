<?php

namespace app\domain\entities;

// require __DIR__ . '/../../../vendor/autoload.php';

class User
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $password,
        public array $role,
        public ?string $img = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->img = $img;
    }
}
