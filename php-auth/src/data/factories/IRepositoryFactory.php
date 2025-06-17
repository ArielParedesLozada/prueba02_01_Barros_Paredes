<?php
namespace app\data\factories;

use app\data\repositorio\IRepository;

interface IRepositoryFactory
{
    public function generateRepository() : IRepository;
}
