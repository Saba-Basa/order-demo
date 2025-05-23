<?php

/*
CRUD: findbyId(), findAll(), create(), update(), delete()

use casees:
    product listing page
    product detail page
    order creation
*/


namespace App\Core\Contracts;

interface ProductRepositoryInterface extends RepositoryInterface{
        public function findByCategory(string $category): array;
}