<?php

namespace app\data\mysql\models;

// require __DIR__ . '/../../../../vendor/autoload.php';

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ORM\GeneratedValue]
    public ?int $id = null;

    #[ORM\Column(type: "string")]
    public ?string $name = null;

    #[ORM\Column(type: "string", unique: true)]
    public string $email;

    #[ORM\Column(type: "string")]
    public string $password;

    #[ORM\Column(type: "string", nullable: true)]
    public ?string $img;

    #[ORM\Column(type: "simple_array", )]
    public array $roles = ['USER_ROLE'];

    public function __construct(string $email, string $password, ?string $name = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }
}
