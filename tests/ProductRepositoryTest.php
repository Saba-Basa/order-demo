<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Support\TestDatabase;
use App\Repositories\ProductRepository;

class ProductRepositoryTest extends TestCase
{
    private ProductRepository $repository;

    protected function setUp(): void
    {
        $connection = TestDatabase::setUp();
        $this->repository = new ProductRepository($connection);
    }

    protected function tearDown(): void
    {
        TestDatabase::tearDown();
    }

    public function testFindById()
    {
        $connection = TestDatabase::setUp();
        $connection->execute(
            "INSERT INTO products (name, price, category, description, download_link) VALUES (?, ?, ?, ?, ?)",
            ['product1', 9.99, 'software', 'test description', 'https://example.com/download']
        );
        $productId = (int) $connection->lastInsertId();
        $result = $this->repository->findById(id: $productId);
        $this->assertNotNull($result);
        // print_r($result);
        // print_r($productId);
        // print_r('\n');
        // print_r($result['id']);
        $this->assertEquals($productId, $result['id']);
        $this->tearDown();
    }

    public function testFindAll()
    {
        $connection = TestDatabase::setUp();

        // Produkte einfügen
        $products = [
            ['product1', 9.99, 'software', 'test description', 'https://example.com/download'],
            ['product2', 9.99, 'software', 'test description', 'https://example.com/download'],
            ['product3', 9.99, 'software', 'test description', 'https://example.com/download']
        ];

        foreach ($products as $product) {
            $connection->execute(
                "INSERT INTO products (name, price, category, description, download_link) VALUES (?, ?, ?, ?, ?)",
                $product
            );
        }

        // Beispiel: hole alle Produkte
        $result = $this->repository->findAll();

        // Prüfe, ob die Namen korrekt sind
        $names = array_column($result, 'name');

        $this->assertContains('product1', $names);
        $this->assertContains('product2', $names);
        $this->assertContains('product3', $names);

        $this->tearDown();
    }

}