<?php

require_once __DIR__ . '/Database.php';

class Payment
{
    private Database $database;
    private ?int $paymentId = null;

    public function __construct(Database $database) { $this->database = $database; }

    public function addPayment(int $billingId, float $amountPaid, string $paymentDate, string $method): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO payments (billing_id, amount_paid, payment_date, method) VALUES (?, ?, ?, ?)');
        $statement->bind_param('idss', $billingId, $amountPaid, $paymentDate, $method);
        $result = $statement->execute(); $statement->close(); return $result;
    }

    /** @return array<string, mixed>|null */
    public function getPaymentById(int $paymentId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM payments WHERE payment_id = ?');
        $statement->bind_param('i', $paymentId); $statement->execute();
        $payment = $statement->get_result()->fetch_assoc(); $statement->close();
        $this->paymentId = $payment === null ? null : (int) $payment['payment_id']; return $payment;
    }

    public function updatePayment(int $paymentId, int $billingId, float $amountPaid, string $paymentDate, string $method): bool
    {
        $statement = $this->connection()->prepare('UPDATE payments SET billing_id = ?, amount_paid = ?, payment_date = ?, method = ? WHERE payment_id = ?');
        $statement->bind_param('idssi', $billingId, $amountPaid, $paymentDate, $method, $paymentId);
        $result = $statement->execute(); $statement->close(); return $result;
    }

    public function deletePayment(int $paymentId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM payments WHERE payment_id = ?');
        $statement->bind_param('i', $paymentId); $result = $statement->execute(); $statement->close(); return $result;
    }

    public function getAllPayments(): mysqli_result
    {
        return $this->connection()->query('SELECT p.*, b.amount AS bill_amount FROM payments p JOIN billing b ON p.billing_id = b.billing_id ORDER BY payment_date DESC');
    }

    public function getBillings(): mysqli_result { return $this->connection()->query('SELECT * FROM billing'); }

    private function connection(): mysqli { return $this->database->getConnection(); }
}
