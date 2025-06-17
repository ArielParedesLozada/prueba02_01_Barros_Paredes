<?php

namespace app\infraestructure\unitofwork;

use app\data\repositorio\SQLiteRepositoryWrapper;
use app\data\sqlite\SQLiteDatabase;

class UnitOfWorkSQLite
{
    private SQLiteRepositoryWrapper $userRepository;

    public function __construct(SQLiteRepositoryWrapper $repo)
    {
        $this->userRepository = $repo;
    }

    public function users(): SQLiteRepositoryWrapper
    {
        return $this->userRepository;
    }

    public function commit(): void
    {
        SQLiteDatabase::$entityManager->flush();
    }

    public function rollback(): void
    {
        SQLiteDatabase::$entityManager->clear();
    }
}