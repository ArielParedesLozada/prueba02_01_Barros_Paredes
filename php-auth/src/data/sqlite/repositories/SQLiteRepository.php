<?php

namespace app\data\sqlite\repositories;

use app\config\Envs;
use app\data\sqlite\SQLiteDatabase;
use app\domain\errors\CustomError;
use Doctrine\ORM\Tools\SchemaTool;
use Exception;

// require __DIR__ . '/../../../../vendor/autoload.php';

class SQLiteRepository
{
    private function createSchemas() {
        $database = __DIR__."/../../../../".Envs::get('SQLITE_DB');
        if (file_exists($database)) {
            return;
        }
        try {
            $metadata = SQLiteDatabase::$entityManager->getMetadataFactory()->getAllMetadata();
            if (empty($metadata)) {
                return;
            }
            $schemaTool = new SchemaTool(SQLiteDatabase::$entityManager);
            $schemaTool->dropSchema($metadata);
            $schemaTool->createSchema($metadata);
        } catch (\Throwable $th) {
            throw CustomError::internalserver("Error al crear la base ".$th->getMessage());
        }
    }
    public function getRepository(string $model)
    {
        $this->createSchemas();
        if (!SQLiteDatabase::$entityManager) {
            throw new Exception("Base no inicializada", 500);
        }
        return SQLiteDatabase::$entityManager->getRepository($model);
    }
}
