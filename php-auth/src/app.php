<?php

namespace app;

require __DIR__ . '/../vendor/autoload.php';

use app\config\Envs;
use app\data\mysql\MySQLDatabase;
use app\data\sqlite\SQLiteDatabase;
use app\presentation\AppServer;

function main(): void
{
   
   MySQLDatabase::connect([
        'host'     => Envs::get('MYSQL_HOST'),
        'port'     => Envs::getInt('MYSQL_PORT'),
        'database'   => Envs::get('MYSQL_DATABASE'),
        'user'     => Envs::get('MYSQL_USER'),
        'password' => Envs::get('MYSQL_PASSWORD') ?? '',
        'charset'  => 'utf8mb4',
    ]);

    new AppServer([
        'port' => Envs::getInt('PORT'),
    ]);
}

main();
