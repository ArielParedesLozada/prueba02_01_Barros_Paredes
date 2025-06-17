<?php

namespace app\data\sqlite;

use app\data\Database;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// require __DIR__ . '/../../../vendor/autoload.php';

class SQLiteDatabase extends Database
{
    public static EntityManager $entityManager;
    public static function connect(array $options)
    {
        try {
            $database = $options['database'];
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__.'/./models/'],
                isDevMode: true,
            );
            $connection = DriverManager::getConnection([
                'driver' => 'pdo_sqlite',
                'path' => __DIR__."/../../../$database",
            ], $config);
            self::$entityManager = new EntityManager($connection, $config);
            return true;
        } catch (\Throwable $th) {
            "Error de conexion ".$th->getMessage();
            throw $th;
        }
    }
}
