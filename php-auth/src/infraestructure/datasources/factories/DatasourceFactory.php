<?php

namespace app\infraestructure\datasources\factories;

use app\data\mysql\MySQLDatabase;
use app\data\sqlite\SQLiteDatabase;

class DatasourceFactory
{
    public static function generateDataSource(string $datasource): IDataSourceFactory
    {
        switch ($datasource) {
            case MySQLDatabase::class:
                return new MySQLFactory();
            case SQLiteDatabase::class:
                return new SQLiteFactory();
            default:
                return new SQLiteFactory();
        }
    }
}
