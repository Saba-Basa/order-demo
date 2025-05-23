<?php

namespace App\Core\Contracts;


/*
Repositories = single entity data access (products, customers, orders)
Services = business logic that uses multiple repositories
Checkout = business process that creates customer → order → order items

-> Services orchestrate repositories, repositories don't orchestrate other repositories.RetryClaude can make mistakes. Please double-check responses.
*/

interface CheckoutServiceInterface 
{
    public function processOrder(array $cartItems, string $customerName, string $customerEmail): int;
}