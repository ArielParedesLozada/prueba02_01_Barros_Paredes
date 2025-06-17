<?php

namespace app\presentation\middlewares;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\config\JwtAdapter;
use app\data\sqlite\models\User;
use app\data\sqlite\repositories\SQLiteRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $headers = $request->getHeader('Authorization');
        $authorization = $headers[0] ?? '';

        if (!$authorization) {
            return $this->unauthorized('No token provided.');
        }

        if (!str_starts_with($authorization, 'Bearer ')) {
            return $this->unauthorized('Invalid Bearer token.');
        }

        $token = substr($authorization, 7);

        try {
            $payload = JwtAdapter::validateToken($token);
            if (!$payload || !isset($payload['id'])) {
                return $this->unauthorized('Invalid token.');
            }

            $user = (new SQLiteRepository())->getRepository(User::class)->find($payload['id']);

            if (!$user) {
                return $this->unauthorized('User not found.');
            }

            // Adjuntar usuario al request
            $request = $request->withAttribute('user', $user);

            return $handler->handle($request);
        } catch (\Throwable $e) {
            return $this->unauthorized('Invalid or expired token.');
        }
    }

    private function unauthorized(string $message): ResponseInterface
    {
        $response = new \Nyholm\Psr7\Response();
        $response->getBody()->write(json_encode(['error' => $message]));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }
}
