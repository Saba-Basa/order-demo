<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Database\MySQLConnection;
use PDO;

class MySQLConnectionTest extends TestCase
{
    private array $config;
    private $connection;

    protected function setUp(): void
{
    $this->config = require __DIR__ . "/../config/database.php";
    
    // Reset singleton
    $reflection = new \ReflectionClass(MySQLConnection::class);
    $instance = $reflection->getProperty('instance');
    $instance->setAccessible(true);
    $instance->setValue(null);
    
    // Start transaction for rollback
    $this->connection = MySQLConnection::getInstance($this->config);
    $this->connection->getConnection()->beginTransaction();
}

protected function tearDown(): void
{
    // Rollback all changes made during the test
    if ($this->connection && $this->connection->isConnected()) {
        $this->connection->getConnection()->rollBack();
    }
    
    // Reset singleton
    $reflection = new \ReflectionClass(MySQLConnection::class);
    $instance = $reflection->getProperty('instance');
    $instance->setAccessible(true);
    $instance->setValue(null);
}

    public function testInstance()
    {
        $i1 = MySQLConnection::getInstance($this->config);
        $this->assertInstanceOf(MySQLConnection::class, $i1);
        $i2 = MySQLConnection::getInstance();
        //same without config
        $this->assertSame($i1, $i2);
        $i3 = MySQLConnection::getInstance();
        $this->assertSame($i1, $i3);
        $this->assertSame($i2, $i3);
    }

    public function testgetConnection()
    {
        $i = MySQLConnection::getInstance($this->config);
        $c = $i->getConnection();
        $this->assertInstanceOf(PDO::class, $c);
    }

    public function testisConnected()
    {
        $i = MySQLConnection::getInstance($this->config);
        $i->getConnection();
        $this->assertTrue($i->isConnected());
    }

    public function testDiconnect()
    {
        $i = MySQLConnection::getInstance($this->config);
        $i->getConnection();
        $i->getConnection();
        $this->assertTrue($i->isConnected());
        $i->disconnect();

        $reflection = new \ReflectionClass($i);
        $connectionProperty = $reflection->getProperty('connection');
        $connectionProperty->setAccessible(true);

        $this->assertNull($connectionProperty->getValue($i));
    }
    public function testQuery()
    {
        $i = MySQLConnection::getInstance($this->config);
        $r = $i->query('show tables;');
        $this->assertIsArray($r);
        $this->assertNotEmpty($r);
        /*
        +--------------------+
        | Tables_in_ordersys |
        +--------------------+
        | customers          |
        | order_items        |
        | orders             |
        | products           |
        +--------------------+
        */
        // print_r($r);
        /*
        {
        [0] => Array
        (
            [Tables_in_ordersys] => customers
        )
        [1] => ...
        }
        */
        /*
        array_column(array $array, int|string|null $column_key, int|string|null $index_key = null): array
        */
        $indexKey = array_column($r, 'Tables_in_ordersys');
        // print_r($indexKey[0]);
        $this->assertEquals($indexKey[0], 'customers');
        $this->assertEquals($indexKey[1], 'order_items');
        $this->assertEquals($indexKey[2], 'orders');
        $this->assertEquals($indexKey[3], 'products');
    }
    public function testExecute(){
        $i = MySQLConnection::getInstance($this->config);
        $i->execute("INSERT INTO customers (name, email) VALUES ('Max Mustermann','max.mustermann@mail.com');");
        $n = $i->query("SELECT COUNT(*) FROM customers;");
        $k= array_column($n,"COUNT(*)");
        $this->assertEquals($k[0],1);
        $i->execute("DELETE FROM customers;");
        $m = $i->query("SELECT COUNT(*) FROM customers;");
        $j = array_column($m,"COUNT(*)");
        $this->assertEquals($j[0],0);
    }


}