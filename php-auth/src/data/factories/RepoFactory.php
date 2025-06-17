<?php

namespace app\data\factories;

use app\data\mysql\MySQLDatabase;
use app\data\sqlite\SQLiteDatabase;

class RepoFactory
{
    public static function generateRepository(string $database, string $entity): IRepositoryFactory
    {
        switch ($database) {
            case MySQLDatabase::class:
                return new MySQLRepoFactory($entity);
            case SQLiteDatabase::class:
                return new SQLiteRepoFactory($entity);
            default:
                return new SQLiteRepoFactory($entity);
                break;
        }
    }
}
