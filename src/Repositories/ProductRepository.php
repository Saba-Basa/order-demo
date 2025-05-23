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
        $sql = "SELECT * FROM products WHERE id = ?";
        $results = $this->queryExecutor->query($sql, [$id]);
        return $results[0] ?? null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM products ORDER BY name";
        return $this->queryExecutor->query($sql);
    }
    public function create(array $data): int
    {
        return 0;
    }
    public function delete(int $id): bool
    {
        return false;
    }
    public function update(int $id, array $data): bool
    {
        return false;
    }
    public function findByCategory(string $category): array
    {
        return [];
    }

}
