<?php

namespace app\infraestructure\datasources;

use app\config\BycryptAdapter;
use app\data\mysql\models\User;
use app\data\mysql\repositories\MySQLRepository;
use app\data\repositorio\MySQLRepositoryWrapper;
use app\domain\datasources\AuthDatasource;
use app\domain\dtos\auth\LoginUserDto;
use app\domain\dtos\auth\RegisterUserDto;
use app\domain\errors\CustomError;
use app\infraestructure\mappers\UserMapper;

class AuthDatasourceImplMySQL extends AuthDatasource
{
    private static ?AuthDatasourceImplMySQL $instance = null;

    private MySQLRepositoryWrapper $userRepo;

    private function __construct(
        private $hashPassword = [BycryptAdapter::class, 'hash'],
        private $comparePassword = [BycryptAdapter::class, 'compare']
    ) {
        // ✅ Inyectamos correctamente con el repositorio base y entidad User
        $baseRepository = new MySQLRepository();
        $this->userRepo = new MySQLRepositoryWrapper($baseRepository, User::class);
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login(LoginUserDto $dto)
    {
        try {
            $user = $this->userRepo->findByEmail($dto->email);
            if (!$user) {
                throw CustomError::badRequest("User does not exist - email");
            }

            $isMatching = call_user_func($this->comparePassword, $dto->password, $user->password);
            if (!$isMatching) {
                throw CustomError::badRequest("Invalid password");
            }

            return UserMapper::userEntityFromObject($user);
        } catch (\Throwable $th) {
            if ($th instanceof CustomError) throw $th;
            throw CustomError::internalServer($th->getMessage());
        }
    }

    public function register(RegisterUserDto $dto)
    {
        try {
            $existing = $this->userRepo->findByEmail($dto->email);
            if ($existing) {
                throw CustomError::badRequest("User already exists");
            }

            $user = new User(
                $dto->email,
                call_user_func($this->hashPassword, $dto->password),
                $dto->name ?? null
            );

            $this->userRepo->save($user); 

            return UserMapper::userEntityFromObject($user);
        } catch (\Throwable $th) {
            if ($th instanceof CustomError) throw $th;
            throw CustomError::internalServer($th->getMessage());
        }
    }
}
