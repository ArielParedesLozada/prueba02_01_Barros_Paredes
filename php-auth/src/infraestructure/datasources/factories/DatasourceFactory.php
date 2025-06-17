<?php

namespace app\infraestructure\datasources\factories;

use app\domain\datasources\AuthDatasource;
use app\infraestructure\datasources\AuthDatasourceImplMySQL;
use app\infraestructure\datasources\AuthDatasourceImplSQLite;

class DatasourceFactory
{
    public static function generateDataSource(string $datasource): IDataSourceFactory
    {
        switch ($datasource) {
            case AuthDatasourceImplMySQL::class:
                return new MySQLFactory();
            case AuthDatasourceImplSQLite::class:
                return new SQLiteFactory();
            default:
                return new SQLiteFactory();
        }
    }
}
