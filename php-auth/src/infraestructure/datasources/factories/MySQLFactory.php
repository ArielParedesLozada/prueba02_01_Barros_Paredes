<?php
namespace app\infraestructure\datasources\factories;

use app\data\mysql\repositories\MySQLRepository;
use app\domain\datasources\AuthDatasource;
use app\infraestructure\datasources\AuthDatasourceImplMySQL;

class MySQLFactory implements IDataSourceFactory
{
    public function generateDatabase() : AuthDatasource{
        return AuthDatasourceImplMySQL::getInstance();
    }
}