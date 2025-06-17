<?php

namespace app\presentation;

// require __DIR__ . '/../../vendor/autoload.php';

use app\presentation\auth\AuthRoutes;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class AppRoutes
{
    public  static function getRoutes(App $app)
    {
        $app->get('/', function ($request, $response) {
            $response->getBody()->write("Servidor PHP funcionando correctamente.");
            return $response;
        });
        $app->group('/api/auth', function (RouteCollectorProxy $group) {
            AuthRoutes::routes($group);
        });
    }
}
