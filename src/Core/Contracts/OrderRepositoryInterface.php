<?php

namespace App\Core\Contracts;

interface OrderRepositoryInterface extends RepositoryInterface
{
    public function findByCustomerId(int $customerId): array;
    public function findWithItems(int $id): ?array;
}