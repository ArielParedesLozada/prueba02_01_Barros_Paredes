<?php

namespace app\data\repositorio;

use app\data\sqlite\repositories\SQLiteRepository;
use Doctrine\ORM\EntityRepository;
use app\domain\errors\CustomError;

class MySQLRepositoryWrapper implements IRepository
{
    private EntityRepository $repository;

    public function __construct(SQLiteRepository $baseRepository, string $entity)
    {
        $this->repository = $baseRepository->getRepository($entity);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function findById(int $id): ?object
    {
        return $this->repository->find($id);
    }

    public function save(object $entity): bool
    {
        try {
            $em = $this->repository->getEntityManager();
            $em->persist($entity);
            $em->flush();
            return true;
        } catch (\Throwable $th) {
            throw CustomError::internalServer("Error al guardar: " . $th->getMessage());
        }
    }

    public function delete(object $entity): bool
    {
        try {
            $em = $this->repository->getEntityManager();
            $em->remove($entity);
            $em->flush();
            return true;
        } catch (\Throwable $th) {
            throw CustomError::internalServer("Error al eliminar: " . $th->getMessage());
        }
    }
}
