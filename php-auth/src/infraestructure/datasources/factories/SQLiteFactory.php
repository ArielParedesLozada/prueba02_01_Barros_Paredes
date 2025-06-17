<?php
namespace app\infraestructure\datasources\factories;

use app\domain\datasources\AuthDatasource;
use app\infraestructure\datasources\AuthDatasourceImplSQLite;

class SQLiteFactory implements IDataSourceFactory
{
    public function generateDatabase() : AuthDatasource{
        return AuthDatasourceImplSQLite::getInstance();
    }
}