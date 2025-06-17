<?php

namespace app\data\factories;

use app\data\mysql\repositories\MySQLRepository;
use app\data\repositorio\IRepository;
use app\data\repositorio\MySQLRepositoryWrapper;

class MySQLRepoFactory implements IRepositoryFactory
{
    public MySQLRepositoryWrapper $repo;
    public function __construct(string $entity)
    {
        $repo_1 = new MySQLRepository();
        $this->repo = new MySQLRepositoryWrapper($repo_1, $entity);
    }
    public function generateRepository(): IRepository
    {
        return $this->repo;
    }
}
