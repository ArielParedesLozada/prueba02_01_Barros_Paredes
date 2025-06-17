<?php

namespace app\data\factories;

use app\data\mysql\repositories\MySQLRepository;
use app\data\repositorio\IRepository;
use app\data\repositorio\MySQLRepositoryWrapper;
use app\data\repositorio\SQLiteRepositoryWrapper;
use app\data\sqlite\repositories\SQLiteRepository;

class SQLiteRepoFactory implements IRepositoryFactory
{
    public SQLiteRepositoryWrapper $repo;
    public function __construct(string $entity)
    {
        $repo_1 = new SQLiteRepository();
        $this->repo = new SQLiteRepositoryWrapper($repo_1, $entity);
    }
    public function generateRepository(): IRepository
    {
        return $this->repo;
    }
}
