<?php
namespace APP\Core\Contracts;
interface TransactionInterface
{
    public function beginTransaction(): bool;
    public function commit(): bool;
    public function rollback(): bool;
    public function inTransaction(): bool;
}