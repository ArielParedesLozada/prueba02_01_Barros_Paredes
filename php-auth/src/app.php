<?php

namespace app;

require __DIR__ . '/../vendor/autoload.php';

use app\config\Envs;
use app\data\sqlite\SQLiteDatabase;
use app\presentation\AppServer;

function main(): void
{
    SQLiteDatabase::connect([
        'database' => Envs::get('SQLITE_DB'),
    ]);

    new AppServer([
        'port' => Envs::getInt('PORT'),
    ]);
}

main();
