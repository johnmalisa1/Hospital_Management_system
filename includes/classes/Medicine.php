<?php

require_once __DIR__ . '/Database.php';

class Medicine
{
    private Database $database;
    private ?int $medicineId = null;

    public function __construct(Database $database) { $this->database = $database; }

    public function addMedicine(string $name, string $description, int $quantity, int $price): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO medicines (name, description, quantity, price) VALUES (?, ?, ?, ?)');
        $statement->bind_param('ssii', $name, $description, $quantity, $price);
        $result = $statement->execute(); $statement->close(); return $result;
    }

    /** @return array<string, mixed>|null */
    public function getMedicineById(int $medicineId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM medicines WHERE medicine_id = ?');
        $statement->bind_param('i', $medicineId); $statement->execute();
        $medicine = $statement->get_result()->fetch_assoc(); $statement->close();
        $this->medicineId = $medicine === null ? null : (int) $medicine['medicine_id']; return $medicine;
    }

    public function updateMedicine(int $medicineId, string $name, string $description, int $quantity, int $price): bool
    {
        $statement = $this->connection()->prepare('UPDATE medicines SET name = ?, description = ?, quantity = ?, price = ? WHERE medicine_id = ?');
        $statement->bind_param('ssiii', $name, $description, $quantity, $price, $medicineId);
        $result = $statement->execute(); $statement->close(); return $result;
    }

    public function deleteMedicine(int $medicineId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM medicines WHERE medicine_id = ?');
        $statement->bind_param('i', $medicineId); $result = $statement->execute(); $statement->close(); return $result;
    }

    public function getAllMedicines(): mysqli_result { return $this->connection()->query('SELECT * FROM medicines ORDER BY medicine_id DESC'); }

    public function getTotalQuantity(): ?int
    {
        $row = $this->connection()->query('SELECT SUM(quantity) AS total FROM medicines')->fetch_assoc();
        return $row['total'] === null ? null : (int) $row['total'];
    }

    private function connection(): mysqli { return $this->database->getConnection(); }
}
