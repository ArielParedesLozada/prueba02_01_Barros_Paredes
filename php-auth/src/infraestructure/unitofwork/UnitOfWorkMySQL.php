<?php

namespace app\infraestructure\unitofwork;

use app\data\mysql\MySQLDatabase;
use app\data\repositorio\MySQLRepositoryWrapper;

class UnitOfWorkMySQL
{
    private MySQLRepositoryWrapper $userRepository;

    public function __construct(MySQLRepositoryWrapper $repo)
    {
        $this->userRepository = $repo;
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
