<?php

namespace app\data\repositorio;

use app\data\sqlite\repositories\SQLiteRepository;
use app\data\sqlite\SQLiteDatabase;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use app\domain\errors\CustomError;

class SQLiteRepositoryWrapper implements IRepository
{
    private EntityRepository $repository;
    private EntityManagerInterface $em;

    public function __construct(SQLiteRepository $baseRepository, string $entity)
    {
        $this->repository = $baseRepository->getRepository($entity);
        $this->em = SQLiteDatabase::$entityManager;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->repository->find($id);
    }

    public function findByEmail(string $email): ?object
    {
        return $this->repository->findOneBy(['email' => $email]);
    }

    public function save(object $entity): bool
    {
        try {
            $this->em->persist($entity);
            return true;
        } catch (\Throwable $th) {
            throw CustomError::internalServer("Error al guardar: " . $th->getMessage());
        }
    }

    public function delete(object $entity): bool
    {
        try {
            $this->em->remove($entity);
            return true;
        } catch (\Throwable $th) {
            throw CustomError::internalServer("Error al eliminar: " . $th->getMessage());
        }
    }
}
