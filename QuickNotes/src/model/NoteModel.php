<?php

declare(strict_types = 1);

namespace QuickNotes\model;

use PDO;
use Throwable;

use QuickNotes\Exception\NotFoundException;
use QuickNotes\Exception\StorageException;
use QuickNotes\model\AbstractModel;
use QuickNotes\model\ModelInterface;

class NoteModel extends AbstractModel implements ModelInterface
{ 
    public function list(
        int $pageSize, 
        int $pageNumber, 
        string $sortBy, 
        string $sortOrder
    ): array
    {
        return $this->findBy(null, $pageSize, $pageNumber, $sortBy, $sortOrder);
    }

    public function search(
        string $phrase,
        int $pageSize, 
        int $pageNumber, 
        string $sortBy, 
        string $sortOrder
    ): array
    {
        return $this->findBy($phrase, $pageSize, $pageNumber, $sortBy, $sortOrder);
    }

    public function count(): int
    {
        try {
            $query = "SELECT count(*) AS cn FROM notes";
            $result = $this->connection->query($query, PDO::FETCH_ASSOC);
            $result = $result->fetch();
            if ($result === false) {
                throw new StorageException('Błąd przy pobraniu ilości notatek', 400);
            }
            
            return (int) $result['cn'];
        } catch(Throwable $e) {
            throw new StorageException('Nie udało się pobrać informacji o liczbie notatek', 400, $e);
        }
    }
    
    public function searchCount(string $phrase): int
    {
        try {
            $phrase = $this->connection->quote('%' . $phrase . '%', PDO::PARAM_STR);
            $query = "SELECT count(*) AS cn FROM notes WHERE title LIKE ($phrase) ";
            $result = $this->connection->query($query, PDO::FETCH_ASSOC);
            $result = $result->fetch();
            if ($result === false) {
                throw new StorageException('Błąd przy pobraniu ilości notatek', 400);
            }
            
            return (int) $result['cn'];
        } catch(Throwable $e) {
            throw new StorageException('Nie udało się pobrać informacji o liczbie notatek', 400, $e);
        }
    }

    public function get(int $id): array
    {
        try {
            $query = "SELECT * FROM notes WHERE id = $id";
            $result = $this->connection->query($query, PDO::FETCH_ASSOC);
            $note = $result->fetch();
        } catch(Throwable $e) {
            throw new StorageException('Nie udało się pobrać notatki', 400, $e);
        }

        if (!$note) {
            throw new NotFoundException("Notatka o id: $id nie istnieje!", 400, $e);
        }

        return $note;
    }

    public function create(array $data): void
    {
        try {
            $title = $this->connection->quote($data['title']);
            $description = $this->connection->quote($data['description']);
            $created = $this->connection->quote(date('Y-m-d H:i:s'));

            $query = "
                INSERT INTO notes(title, description, created)
                VALUES($title, $description, $created)
            ";

            $this->connection->exec($query);

        } catch(Throwable $e) {
            throw new StorageException('Nie udało się utworzyć notatki');
        }
    }

    public function edit(int $id, array $data): void
    {
        try {
            $title = $this->connection->quote($data['title']);
            $description = $this->connection->quote($data['description']);

            $query = "
                UPDATE notes
                SET title = $title, description = $description
                WHERE id = $id
            ";

            $this->connection->exec($query);

        } catch(Throwable $e) {
            throw new StorageException('Nie udało się edytować notatki');
        }
    }

    public function delete(int $id): void
    {
        try {    
            $query = "DELETE FROM notes WHERE id = $id";
            $this->connection->exec($query);
        } catch(Throwable $e) {
            throw new StorageException('Nie udało się usunąć notatki');
        }
    }

    private function findBy(
        ?string $phrase,
        int $pageSize, 
        int $pageNumber, 
        string $sortBy, 
        string $sortOrder
    )
    {
        try {
            $limit = $pageSize;
            $offset = ($pageNumber - 1) * $pageSize;

            if (!in_array($sortBy, ['title', 'created'])) {
                $sortBy = 'title';
            }

            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'desc';
            }

            $wherePart = '';
            if ($phrase) {
                $phrase = $this->connection->quote('%' . $phrase . '%', PDO::PARAM_STR);
                $wherePart = "WHERE title LIKE ($phrase)";    
            }

            $query = "
                SELECT id, title, created 
                FROM notes
                $wherePart 
                ORDER BY $sortBy $sortOrder
                LIMIT $offset, $limit";

            $result = $this->connection->query($query, PDO::FETCH_ASSOC);
            return $result->fetchAll();
        } catch(Throwable $e) {
            throw new StorageException('Nie udało się wyszukać notatek', 400, $e);
        }
    }
}