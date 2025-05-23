<?php
namespace Tests\Support;

use App\Core\Database\MySQLConnection;

class TestDatabase
{
    private static ?MySQLConnection $connection = null;
    private static array $config;

    public static function setUp(): MySQLConnection
    {
        if (self::$connection === null) {
            self::$config = require __DIR__ . "/../../config/database.php";
            
            // Reset singleton
            $reflection = new \ReflectionClass(MySQLConnection::class);
            $instance = $reflection->getProperty('instance');
            $instance->setAccessible(true);
            $instance->setValue(null);
            
            self::$connection = MySQLConnection::getInstance(self::$config);
            self::$connection->beginTransaction();
        }
        
        return self::$connection;
    }

    public static function tearDown(): void
    {
        if (self::$connection && self::$connection->isConnected()) {
            self::$connection->rollback();
        }
        
        // Reset singleton
        $reflection = new \ReflectionClass(MySQLConnection::class);
        $instance = $reflection->getProperty('instance');
        $instance->setAccessible(true);
        $instance->setValue(null);
        
        self::$connection = null;
    }
}