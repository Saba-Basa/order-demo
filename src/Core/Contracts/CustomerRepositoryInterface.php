<?php

namespace App\Core\Contracts;

interface CustomerRepositoryInterface extends RepositoryInterface
{
    public function findByEmail(string $email): ?array;
}
