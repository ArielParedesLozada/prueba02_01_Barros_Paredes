<?php
namespace app\infraestructure\singleton;

use app\data\Database;
use Error;

class GlobalDatabase
{
    public static ?GlobalDatabase $instance = null;
    public Database $connection;

    private function __construct(Database $conn) {
        $this->connection = $conn;
    }

    public  static function getInstance(?Database $conn)
    {
        if (!self::$instance) {
            if (!$conn) {
                throw new Error("Base no inicializada");
            }
            self::$instance = new GlobalDatabase($conn);
        }
        return self::$instance;
    }
}
