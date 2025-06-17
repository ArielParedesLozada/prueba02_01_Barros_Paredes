<?php

namespace app;

require __DIR__ . '/../vendor/autoload.php';

use app\config\Envs;
use app\data\mysql\MySQLDatabase;
use app\data\sqlite\SQLiteDatabase;
use app\infraestructure\singleton\GlobalDatabase;
use app\presentation\AppServer;

function main(): void
{

    $db = new MySQLDatabase([
        'host'     => Envs::get('MYSQL_HOST'),
        'port'     => Envs::getInt('MYSQL_PORT'),
        'database'   => Envs::get('MYSQL_DATABASE'),
        'user'     => Envs::get('MYSQL_USER'),
        'password' => Envs::get('MYSQL_PASSWORD') ?? '',
        'charset'  => 'utf8mb4',
    ]);

    GlobalDatabase::getInstance($db)->connection->connect();

    new AppServer([
        'port' => Envs::getInt('PORT'),
    ]);
}

main();
