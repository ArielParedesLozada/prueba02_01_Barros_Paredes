<?php
namespace app\domain\datasources;
require __DIR__.'/../../../vendor/autoload.php';

use app\domain\dtos\auth\LoginUserDto;
use app\domain\dtos\auth\RegisterUserDto;

abstract class AuthDatasource 
{
    public abstract function login(LoginUserDto $loginUserDto);
    public abstract function register(RegisterUserDto $registerUserDto);
}
