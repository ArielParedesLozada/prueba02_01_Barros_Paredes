<?php

namespace app\infraestructure\datasources;

use app\config\BycryptAdapter;
use app\data\sqlite\models\User;
use app\data\sqlite\repositories\SQLiteRepository;
use app\data\repositorio\SQLiteRepositoryWrapper;
use app\domain\datasources\AuthDatasource;
use app\domain\dtos\auth\LoginUserDto;
use app\domain\dtos\auth\RegisterUserDto;
use app\domain\errors\CustomError;
use app\infraestructure\mappers\UserMapper;
use app\infraestructure\unitofwork\UnitOfWorkSQLite;

class AuthDatasourceImplSQLite extends AuthDatasource
{
    private static ?AuthDatasourceImplSQLite $instance = null;

    private SQLiteRepositoryWrapper $userRepo;
    private UnitOfWorkSQLite $uow;


    private function __construct(
        private $hashPassword = [BycryptAdapter::class, 'hash'],
        private $comparePassword = [BycryptAdapter::class, 'compare']
    ) {
        $baseRepository = new SQLiteRepository();
        $this->userRepo = new SQLiteRepositoryWrapper($baseRepository, User::class);
        $this->uow = new UnitOfWorkSQLite($this->userRepo);
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
        $this->uow->beginTransaction();
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
            $this->uow->commit();
            return UserMapper::userEntityFromObject($user);
        } catch (\Throwable $th) {
            $this->uow->rollback();
            if ($th instanceof CustomError) throw $th;
            throw CustomError::internalServer($th->getMessage());
        }
    }
}
