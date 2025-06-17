<?php

namespace app\infraestructure\repositories;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\domain\datasources\AuthDatasource;
use app\domain\dtos\auth\LoginUserDto;
use app\domain\dtos\auth\RegisterUserDto;
use app\domain\repositories\AuthRepository;

class AuthRepositoryImpl extends AuthRepository
{
    public function __construct(
        private readonly AuthDatasource $authDatasource
    ) {}
    public function login(LoginUserDto $loginUserDto)
    {
        return $this->authDatasource->login($loginUserDto);
    }
    public function register(RegisterUserDto $registerUserDto)
    {
        return $this->authDatasource->register($registerUserDto);
    }
}
