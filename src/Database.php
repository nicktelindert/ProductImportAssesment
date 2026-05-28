<?php

namespace ProductImporter;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private ?PDO $connection = null;

    /**
     * Verkrijg de PDO verbinding.
     * Gegevens worden uit de environment variabelen gehaald (gezet via docker-compose/.env).
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $host = getenv('DB_HOST');
            $db   = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASSWORD');

            if (!$host || !$db || !$user || !$pass) {
                throw new RuntimeException('Database configuratie ontbreekt in de environment variabelen.');
            }

            $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->connection = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                throw new RuntimeException('Fout bij verbinden met de database: ' . $e->getMessage());
            }
        }

        return $this->connection;
    }
}