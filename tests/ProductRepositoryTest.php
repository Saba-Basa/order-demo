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
        $result = $this->repository->findAll();
        $names = array_column($result, 'name');

        $this->assertContains('product1', $names);
        $this->assertContains('product2', $names);
        $this->assertContains('product3', $names);

        $this->tearDown();
    }

    public function testcreate()
    {
        $this->setup();

        $products = [
            ['name' => 'product1', 'price' => 9.99, 'category' => 'software', 'description' => 'test description', 'download_link' => 'https://example.com/download'],
            ['name' => 'product2', 'price' => 19.99, 'category' => 'ebook', 'description' => 'test description', 'download_link' => 'https://example.com/download2'],
            ['name' => 'product3', 'price' => 29.99, 'category' => 'course', 'description' => 'test description', 'download_link' => 'https://example.com/download3']
        ];

        foreach ($products as $product) {
            $this->repository->create($product);
        }

        $result = $this->repository->findAll();
        $names = array_column($result, 'name');

        $this->assertContains('product1', $names);
        $this->assertContains('product2', $names);
        $this->assertContains('product3', $names);

        $this->tearDown();
    }

    public function testDelete()
    {
        $this->setup();
        $products = [
            ['name' => 'product1', 'price' => 9.99, 'category' => 'software', 'description' => 'test description', 'download_link' => 'https://example.com/download'],
            ['name' => 'product2', 'price' => 19.99, 'category' => 'ebook', 'description' => 'test description', 'download_link' => 'https://example.com/download2'],
            ['name' => 'product3', 'price' => 29.99, 'category' => 'course', 'description' => 'test description', 'download_link' => 'https://example.com/download3']
        ];

        foreach ($products as $product) {
            $this->repository->create($product);
        }

        $result = $this->repository->findAll();
        $names = array_column($result, 'name');

        $this->assertContains('product1', $names);
        $this->assertContains('product2', $names);
        $this->assertContains('product3', $names);

        $firstP = $result[0]['id'];
        print_r($firstP);
        $this->assertNotEmpty($firstP);
        $delete = $this->repository->delete($firstP);
        $this->assertTrue($delete);
        $falseDelete = $this->repository->delete(99);
        $this->assertFalse($falseDelete);
        $this->tearDown();
    }


}