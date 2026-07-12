<?php

require_once __DIR__ . '/Database.php';

class Equipment
{
    private Database $database;
    private ?int $equipmentId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addEquipment(string $name, string $type, int $quantity, string $status, string $purchaseDate): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO equipment (name, type, quantity, status, purchase_date) VALUES (?, ?, ?, ?, ?)'
        );
        $statement->bind_param('ssiss', $name, $type, $quantity, $status, $purchaseDate);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getEquipmentById(int $equipmentId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM equipment WHERE equipment_id = ?');
        $statement->bind_param('i', $equipmentId);
        $statement->execute();
        $equipment = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($equipment === null) {
            return null;
        }

        $this->equipmentId = (int) $equipment['equipment_id'];

        return $equipment;
    }

    public function updateEquipment(int $equipmentId, string $name, string $type, int $quantity, string $status, string $purchaseDate): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE equipment SET name=?, type=?, quantity=?, status=?, purchase_date=? WHERE equipment_id=?'
        );
        $statement->bind_param('ssissi', $name, $type, $quantity, $status, $purchaseDate, $equipmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteEquipment(int $equipmentId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM equipment WHERE equipment_id = ?');
        $statement->bind_param('i', $equipmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllEquipment(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM equipment ORDER BY purchased_date DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
