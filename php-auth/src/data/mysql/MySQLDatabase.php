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
    public string $database;
    public string $user;
    public string $password;
    public string $port;
    public string $host;
    public string $charset;


    public function __construct(array $options) {
        $this->database = $options['database'];
        $this->user = $options['user'];
        $this->password = $options['password'];
        $this->port = $options['port'];
        $this->host = $options['host'] ?? 'localhost';
        $this->charset = $options['charset'] ?? 'utf8mb4';
    }
    public function connect()
    {
        try {
            $config = ORMSetup::createAttributeMetadataConfiguration(
                paths: [__DIR__ . '/./models/'],
                isDevMode: true,
            );
            $connection = DriverManager::getConnection([
                'driver' => 'pdo_mysql',
                'host'     => $this->host,
                'port'     => $this->port,
                'dbname'   => $this->database,
                'user'     => $this->user,
                'password' => $this->password,
                'charset'  => $this->charset,
            ], $config);
            self::$entityManager = new EntityManager($connection, $config);
            return true;
        } catch (\Throwable $th) {
            "Error de conexion " . $th->getMessage();
            throw $th;
        }
    }
}
