<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private ?PDO $connection = null;

    private function __construct()
    {
        $type = DB_TYPE;
        $host = DB_HOST;
        $name = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;

        $dsn = "$type:host=$host;dbname=$name;charset=utf8";
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \Exception("Database connection failed");
        }
    }

    public static function getInstance(): ?Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function read(string $query, array $data = []): array|false
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result)) {
                return $result;
            }
        } catch (PDOException $e) {
            error_log("Query execution failed: " . $e->getMessage());
        }

        return false;
    }

    public function write(string $query, array $data = []): bool
    {
        if ($this->connection === null) {
            error_log("No database connection established");
            return false;
        }

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            error_log("Query execution failed: " . $e->getMessage() . " | Query: " . $query . " | Data: " . json_encode($data));
            return false;
        }
    }

    public function lastInsertId()
    {
        try {
            $lastInsertId = $this->connection->lastInsertId();
            return $lastInsertId;
        } catch (PDOException $e) {
            error_log("Query execution failed: " . $e->getMessage());
        }

        return false;
    }

    public function beginTransaction(): bool
    {
        if ($this->connection === null) {
            error_log("No database connection established");
            return false;
        }

        try {
            return $this->connection->beginTransaction();
        } catch (PDOException $e) {
            error_log("Failed to begin transaction: " . $e->getMessage());
            return false;
        }
    }

    public function rollBack(): bool
    {
        if ($this->connection === null) {
            error_log("No database connection established");
            return false;
        }

        try {
            return $this->connection->rollBack();
        } catch (PDOException $e) {
            error_log("Failed to roll back transaction: " . $e->getMessage());
            return false;
        }
    }

    public function commit(): bool
    {
        if ($this->connection === null) {
            error_log("No database connection established");
            return false;
        }

        try {
            return $this->connection->commit();
        } catch (PDOException $e) {
            error_log("Failed to commit transaction: " . $e->getMessage());
            return false;
        }
    }

    public function closeConnection(): void
    {
        $this->connection = null;
    }
}
