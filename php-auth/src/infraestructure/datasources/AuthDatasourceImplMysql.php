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
use app\infraestructure\unitofwork\UnitOfWorkMySQL;

class AuthDatasourceImplMySQL extends AuthDatasource
{
    private static ?AuthDatasourceImplMySQL $instance = null;

    private MySQLRepositoryWrapper $userRepo;
    private UnitOfWorkMySQL $uow;

    private function __construct(
        private $hashPassword = [BycryptAdapter::class, 'hash'],
        private $comparePassword = [BycryptAdapter::class, 'compare']
    ) {
        $baseRepository = new MySQLRepository();
        $this->userRepo = new MySQLRepositoryWrapper($baseRepository, User::class);
        $this->uow = new UnitOfWorkMySQL($this->userRepo);
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
            $this->uow->beginTransaction();
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
            $this->uow->commit();
            return UserMapper::userEntityFromObject($user);
        } catch (\Throwable $th) {
            $this->uow->rollback();
            if ($th instanceof CustomError) throw $th;
            throw CustomError::internalServer($th->getMessage());
        }
    }
}
