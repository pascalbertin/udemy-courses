<?php

declare(strict_types = 1);

namespace QuickNotes\model;

use PDO;
use PDOException;
use QuickNotes\Exception\ConfigurationException;
use QuickNotes\Exception\StorageException;

abstract class AbstractModel
{
    protected PDO $connection;

    public function __construct(array $config)
    {
        try {
            $this->validateConfig($config);
            $this->createConnection($config);
        } catch(PDOException $e) {
            throw new StorageException('Connection Error');
        }
    }

    private function validateConfig(array $config): void
    {
        if (
            empty($config['host'])
            || empty($config['user'])
            || empty($config['password'])
            || empty($config['databaseName'])
        ) {
            throw new ConfigurationException('Data connection problem');
        }
    }

    private function createConnection(array $config): void
    {
        $dsn = "mysql:dbname={$config['databaseName']};host={$config['host']}";
        $this->connection = new PDO(
            $dsn,
            $config['user'],
            $config['password']
        );
    }
}
