<?php

require_once __DIR__ . '/Database.php';

class BloodBank
{
    private Database $database;
    private ?int $unitId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addUnit(string $bloodType, int $quantity, string $donorName, string $dateDonated, string $expiryDate, string $status): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO blood_bank (blood_type, quantity, donor_name, date_donated, expiry_date, status) VALUES (?, ?, ?, ?, ?, ?)'
        );
        $statement->bind_param('sissss', $bloodType, $quantity, $donorName, $dateDonated, $expiryDate, $status);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getUnitById(int $unitId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM blood_bank WHERE unit_id = ?');
        $statement->bind_param('i', $unitId);
        $statement->execute();
        $unit = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($unit === null) {
            return null;
        }

        $this->unitId = (int) $unit['unit_id'];

        return $unit;
    }

    public function updateUnit(int $unitId, string $bloodType, int $quantity, string $donorName, string $dateDonated, string $expiryDate, string $status): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE blood_bank SET blood_type=?, quantity=?, donor_name=?, date_donated=?, expiry_date=?, status=? WHERE unit_id = ?'
        );
        $statement->bind_param('sissssi', $bloodType, $quantity, $donorName, $dateDonated, $expiryDate, $status, $unitId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteUnit(int $unitId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM blood_bank WHERE unit_id = ?');
        $statement->bind_param('i', $unitId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllUnits(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM blood_bank ORDER BY date_donated DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
