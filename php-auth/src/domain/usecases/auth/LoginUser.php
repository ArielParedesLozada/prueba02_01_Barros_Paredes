<?php

namespace app\domain\usecases\auth;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\config\JwtAdapter;
use app\domain\dtos\auth\LoginUserDto;
use app\domain\errors\CustomError;
use app\domain\repositories\AuthRepository;

class LoginUser
{
    public function __construct(
        private AuthRepository $authRepository,
        private $signToken = null,
        //private $signToken = [JwtAdapter::class, 'generateToken'],
    ) {
        $this->signToken = $signToken ?? [JwtAdapter::class, 'generateToken'];
    }

    public function execute(LoginUserDto $loginUserDto): array
    {
        $user = $this->authRepository->login($loginUserDto);

        $token = call_user_func($this->signToken, ['id' => $user->id], '2h');

        if (!$token) {
            throw CustomError::internalServer('Error generating token');
        }

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ];
    }
}
