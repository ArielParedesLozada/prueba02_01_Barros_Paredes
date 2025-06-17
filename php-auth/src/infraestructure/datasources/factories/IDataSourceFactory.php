<?php
namespace app\infraestructure\datasources\factories;

use app\domain\datasources\AuthDatasource;

interface IDataSourceFactory
{
    public function generateDatabase() : AuthDatasource;
}
