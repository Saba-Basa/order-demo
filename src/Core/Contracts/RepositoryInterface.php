<?php

namespace App\Core\Contracts;

interface RepositoryInterface{
    public function findById($id):?array;
    public function findAll(): array;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}