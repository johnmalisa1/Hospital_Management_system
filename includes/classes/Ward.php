<?php

require_once __DIR__ . '/Database.php';

class Ward
{
    private Database $database;
    private ?int $wardId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addWard(string $wardName, string $description): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO wards (ward_name, description) VALUES (?, ?)');
        $statement->bind_param('ss', $wardName, $description);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getWardById(int $wardId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM wards WHERE ward_id = ?');
        $statement->bind_param('i', $wardId);
        $statement->execute();
        $ward = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($ward === null) {
            return null;
        }

        $this->wardId = (int) $ward['ward_id'];

        return $ward;
    }

    public function updateWard(int $wardId, string $wardName, string $description): bool
    {
        $statement = $this->connection()->prepare('UPDATE wards SET ward_name = ?, description = ? WHERE ward_id = ?');
        $statement->bind_param('ssi', $wardName, $description, $wardId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteWard(int $wardId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM wards WHERE ward_id = ?');
        $statement->bind_param('i', $wardId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllWards(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM wards ORDER BY ward_id DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
