<?php
namespace APP\Core\Contracts;

use PDO;

interface ConnectionInterface{
    public function getConnection(): PDO;
    public function isConnected(): bool;
    public function disconnect(): void;
}