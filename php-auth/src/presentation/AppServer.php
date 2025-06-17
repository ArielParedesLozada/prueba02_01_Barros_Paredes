<?php

namespace app\presentation;

// require __DIR__ . '/../../vendor/autoload.php';

use Slim\Factory\AppFactory;

class AppServer
{
    private int $port;

    public function __construct(array $options)
    {
        $this->port = $options['port'] ?? 3100;

        $app = AppFactory::create();

        // Middleware
        $app->addBodyParsingMiddleware();

        $app->add(function ($request, $handler) {
            $response = $handler->handle($request);
            return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        });

        $app->options('/{routes:.+}', function ($request, $response) {
            return $response;
        });
        // Rutas definidas en AppRoutes
        AppRoutes::getRoutes($app);

        // Ejecutar la app
        $this->run($app);
    }

    private function run($app): void
    {
        echo "Skibidi";
        $app->run(); // Slim maneja la ejecución automáticamente
    }
}
