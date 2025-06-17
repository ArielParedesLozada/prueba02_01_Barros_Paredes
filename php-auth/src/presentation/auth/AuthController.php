<?php

namespace app\presentation\auth;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\domain\dtos\auth\LoginUserDto;
use app\domain\dtos\auth\RegisterUserDto;
use app\domain\errors\CustomError;
use app\domain\repositories\AuthRepository;
use app\domain\usecases\auth\LoginUser;
use app\domain\usecases\auth\RegisterUser;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(
        private readonly AuthRepository $authRepository
    ) {}

    private function handleError(\Throwable $error, Response $response): Response
    {
        if ($error instanceof CustomError) {
            $response->getBody()->write(json_encode(['error' => $error->getMessage()]));
            return $response->withStatus($error->getCode())->withHeader('Content-Type', 'application/json');
        }

        error_log((string)$error);

        $response->getBody()->write(json_encode(['error' => $error->getMessage()]));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    public function registerUser(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        [$error, $dto] = RegisterUserDto::create($data);

        if ($error) {
            $response->getBody()->write(json_encode(['error' => $error]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $result = (new RegisterUser($this->authRepository))->execute($dto);
            $user = $result['user'];
            $response->getBody()->write(json_encode([
                'token' => $result['token'],
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                ],
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            return $this->handleError($e, $response);
        }
    }

    public function loginUser(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        [$error, $dto] = LoginUserDto::create($data);

        if ($error) {
            $response->getBody()->write(json_encode(['error' => $error]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        try {
            $user = (new LoginUser($this->authRepository))->execute($dto);
            $response->getBody()->write(json_encode($user));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            return $this->handleError($e, $response);
        }
    }

    public function getUsers(Request $request, Response $response)
    {
        try {
            $user = $request->getAttribute('user');
            $response->getBody()->write(json_encode([
                //'users' => UserModel::findAll(),
                'user' => [
                    $user
                ],
            ]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Throwable $e) {
            return $response->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
            //  ->withBody(json_encode(['error' => 'Internal Server Error']));
        }
    }
}
