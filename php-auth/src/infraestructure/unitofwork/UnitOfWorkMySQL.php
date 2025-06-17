<?php

namespace app\infrastructure\unitofwork;

use app\data\mysql\repositories\MySQLRepository;
use app\data\mysql\MySQLDatabase;
use app\data\mysql\models\User;
use app\data\repositorio\MySQLRepositoryWrapper;

class UnitOfWorkMySQL
{
    private MySQLRepositoryWrapper $userRepository;

    public function __construct()
    {
        $repo = new MySQLRepository();

        $this->userRepository = new MySQLRepositoryWrapper($repo, User::class);
    }

    public function users(): MySQLRepositoryWrapper
    {
        return $this->userRepository;
    }

    public function commit(): void
    {
        MySQLDatabase::$entityManager->flush();
    }

    public function rollback(): void
    {
        MySQLDatabase::$entityManager->clear();
    }
}
