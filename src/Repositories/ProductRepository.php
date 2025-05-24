<?php

namespace App\Repositories;

use App\Core\Contracts\QueryExecutorInterface;
use App\Core\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private QueryExecutorInterface $queryExecutor;

    public function __construct(QueryExecutorInterface $queryExecutor)
    {
        $this->queryExecutor = $queryExecutor;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM products WHERE id = :id";
        $results = $this->queryExecutor->query($sql, ['id' => $id]);
        return $results[0] ?? null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM products ORDER BY name";
        return $this->queryExecutor->query($sql);
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO products (name, price, category, description, download_link) 
               VALUES (:name, :price, :category, :description, :download_link)";

        $this->queryExecutor->execute($sql, [
            'name' => $data['name'],
            'price' => $data['price'],
            'category' => $data['category'],
            'description' => $data['description'],
            'download_link' => $data['download_link']
        ]);

        return (int) $this->queryExecutor->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $check = "SELECT 1 FROM products WHERE id = :id LIMIT 1;";
        $result = $this->queryExecutor->query($check, ['id' => $id]);

        if (count($result) > 0) {
            $delete = "DELETE FROM products WHERE id = :id;";
            $this->queryExecutor->execute($delete, ['id' => $id]);
            return true;
        }

        return false;
    }

    public function update(int $id, array $data): bool
    {
        $check = "SELECT 1 FROM products WHERE id = :id LIMIT 1;";
        $result = $this->queryExecutor->query($check, ['id' => $id]);
        if (count($result) === 0) {
            return false;
        }

        $allowedFields = ['name', 'price', 'category', 'description', 'download_link'];
        $setParts = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields, true)) {
                $setParts[] = "$key = :$key";
                $params[$key] = $value;
            }
        }

        if (empty($setParts)) {
            return false;
        }

        $sql = "UPDATE products SET " . implode(', ', $setParts) . " WHERE id = :id;";
        return $this->queryExecutor->execute($sql, $params);
    }

    public function findByCategory(string $category): array
    {
        return [];
    }
}