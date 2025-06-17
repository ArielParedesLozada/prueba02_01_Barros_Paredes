<?php

namespace app\data\mysql;

use app\data\Database;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

// require __DIR__ . '/../../../vendor/autoload.php';

class MySQLDatabase extends Database
{
    public static EntityManager $entityManager;
    public static function connect(array $options)
    {
        try {
            $database = $options['database'];
            $user = $options['user'];
            $password = $options['password'];
            $port = $options['port'];
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/./models/'],
                isDevMode: true,
            );
            $connection = DriverManager::getConnection([
                'driver' => 'pdo_mysql',
                'host'     => $options['host'],
                'port'     => $port,
                'dbname'   => $database,
                'user'     => $user,
                'password' => $password,
                'charset'  => $options['charset'] ?? 'utf8mb4',
            ], $config);
            self::$entityManager = new EntityManager($connection, $config);
            return true;
        } catch (\Throwable $th) {
            "Error de conexion " . $th->getMessage();
            throw $th;
        }
    }
}
