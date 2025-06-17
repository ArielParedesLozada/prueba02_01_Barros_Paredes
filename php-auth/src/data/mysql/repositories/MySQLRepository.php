<?php

namespace app\data\mysql\repositories;

use app\config\Envs;
use app\data\mysql\MySQLDatabase;
use app\domain\errors\CustomError;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;

// require __DIR__ . '/../../../../vendor/autoload.php';

class MySQLRepository
{
    private function createSchemas() {
        try {
            $metadata = MySQLDatabase::$entityManager->getMetadataFactory()->getAllMetadata();
            if (empty($metadata)) {
                return;
            }
            $schemaTool = new SchemaTool(MySQLDatabase::$entityManager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        } catch (\Throwable $th) {
            throw CustomError::internalserver("Error al crear la base ".$th->getMessage());
        }
    }
    public function getRepository(string $model)
    {
        // $this->createSchemas();
        if (!MySQLDatabase::$entityManager) {
            throw new Exception("Base no inicializada", 500);
        }
        return MySQLDatabase::$entityManager->getRepository($model);
    }
}
