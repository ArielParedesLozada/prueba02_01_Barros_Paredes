<?php

namespace app\data\repositorio;

interface IRepository
{
    public function findAll(): array;

    public function findById(int $id): object|null;

    public function save(object $entity): bool;

    public function delete(object $entity): bool;
}
