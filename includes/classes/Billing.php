<?php

require_once __DIR__ . '/Database.php';

class Billing
{
    private Database $database;
    private ?int $billingId = null;

    public function __construct(Database $database) { $this->database = $database; }

    public function createBill(int $patientId, float $amount, string $status): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO billing (patient_id, amount, status) VALUES (?, ?, ?)');
        $statement->bind_param('ids', $patientId, $amount, $status);
        $result = $statement->execute(); $statement->close(); return $result;
    }

    /** @return array<string, mixed>|null */
    public function getBillById(int $billingId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM billing WHERE billing_id = ?');
        $statement->bind_param('i', $billingId); $statement->execute();
        $bill = $statement->get_result()->fetch_assoc(); $statement->close();
        $this->billingId = $bill === null ? null : (int) $bill['billing_id']; return $bill;
    }

    public function updateBill(int $billingId, int $patientId, float $amount, string $status): bool
    {
        $statement = $this->connection()->prepare('UPDATE billing SET patient_id = ?, amount = ?, status = ? WHERE billing_id = ?');
        $statement->bind_param('idsi', $patientId, $amount, $status, $billingId);
        $result = $statement->execute(); $statement->close(); return $result;
    }

    public function deleteBill(int $billingId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM billing WHERE billing_id = ?');
        $statement->bind_param('i', $billingId); $result = $statement->execute(); $statement->close(); return $result;
    }

    public function getAllBills(): mysqli_result
    {
        return $this->connection()->query('SELECT b.billing_id, p.name AS patient_name, b.amount, b.status FROM billing b JOIN patients p ON b.patient_id = p.patient_id ORDER BY b.billing_id DESC');
    }

    public function getPaidTotal(): ?float
    {
        $row = $this->connection()->query("SELECT SUM(amount) AS total FROM billing WHERE status = 'Paid'")->fetch_assoc();
        return $row['total'] === null ? null : (float) $row['total'];
    }

    public function countBillsByStatus(string $status): int
    {
        $statement = $this->connection()->prepare('SELECT COUNT(*) AS total FROM billing WHERE status = ?');
        $statement->bind_param('s', $status); $statement->execute();
        $row = $statement->get_result()->fetch_assoc(); $statement->close(); return (int) $row['total'];
    }

    private function connection(): mysqli { return $this->database->getConnection(); }
}
