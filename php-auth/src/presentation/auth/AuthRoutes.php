<?php

namespace app\presentation\auth;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\data\mysql\models\User;
use app\infraestructure\datasources\factories\DatasourceFactory;
use app\infraestructure\repositories\AuthRepositoryImpl;
use app\infraestructure\singleton\GlobalDatabase;
use app\presentation\middlewares\AuthMiddleware;
use Slim\Routing\RouteCollectorProxy;

class AuthRoutes
{
    public  static function routes(RouteCollectorProxy $group)
    {
        $database = GlobalDatabase::getInstance(null);
        $datasource = DatasourceFactory::generateDataSource($database->connection::class, User::class)->generateDatabase();
        $authRepository = new AuthRepositoryImpl($datasource);
        $controller = new AuthController($authRepository);

        $group->post('/login', [$controller, 'loginUser']);
        $group->post('/register', [$controller, 'registerUser']);
        $group->get('/', [$controller, 'getUsers'])->add(new AuthMiddleware());
    }
}
