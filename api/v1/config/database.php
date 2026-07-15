<?php

/**
 * Database Configuration for API
 * Reuses existing database connection settings
 */

class Database
{
    private $host;
    private $user;
    private $password;
    private $database;
    private $pdo;

    public function __construct()
    {
        // Use existing database configuration
        $this->host = "localhost";
        $this->user = "rahbaria_software_team";
        $this->password = "C7L}n}U n<Y}^";
        $this->database = "rahbaria_requestr_rahbarian";
    }

    public function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
            $this->pdo = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            return $this->pdo;
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        if ($this->pdo === null) {
            $this->connect();
        }
        return $this->pdo;
    }
}
