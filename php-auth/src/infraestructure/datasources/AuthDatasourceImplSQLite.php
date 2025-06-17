<?php

namespace app\infraestructure\datasources;

// require __DIR__ . '/../../../vendor/autoload.php';

use app\config\BycryptAdapter;
use app\data\sqlite\models\User;
use app\data\sqlite\repositories\SQLiteRepository;
use app\data\sqlite\SQLiteDatabase;
use app\domain\datasources\AuthDatasource;
use app\domain\dtos\auth\LoginUserDto;
use app\domain\dtos\auth\RegisterUserDto;
use app\domain\errors\CustomError;
use app\infraestructure\mappers\UserMapper;
use Doctrine\ORM\EntityRepository;

class AuthDatasourceImplSQLite extends AuthDatasource
{
    private static ?AuthDatasourceImplSQLite $instance = null;

    private EntityRepository $userRepo;

    private function __construct(
        private $hashPassword = [BycryptAdapter::class, 'hash'],
        private $comparePassword = [BycryptAdapter::class, 'compare']
    ) {
        $this->userRepo = (new SQLiteRepository())->getRepository(User::class);
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
            $user = $this->userRepo->findOneBy(['email' => $dto->email]);
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
            $repo = new SQLiteDatabase();
            $existing = $this->userRepo->findOneBy(['email' => $dto->email]);
            if ($existing) {
                throw CustomError::badRequest("User already exists");
            }

            $user = new User($dto->email, call_user_func($this->hashPassword, $dto->password), $dto->name ?? null);
            $repo::$entityManager->persist($user);
            $repo::$entityManager->flush();

            return UserMapper::userEntityFromObject($user);
        } catch (\Throwable $th) {
            if ($th instanceof CustomError) throw $th;
            throw CustomError::internalServer($th->getMessage());
        }
    }
}
