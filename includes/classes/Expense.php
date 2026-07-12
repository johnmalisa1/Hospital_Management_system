<?php

require_once __DIR__ . '/Database.php';

class Expense
{
    private Database $database;
    private ?int $expenseId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addExpense(string $category, float $amount, string $expenseDate, string $notes): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO expenses (category, amount, expense_date, notes) VALUES (?, ?, ?, ?)'
        );
        $statement->bind_param('sdss', $category, $amount, $expenseDate, $notes);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getExpenseById(int $expenseId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM expenses WHERE expense_id = ?');
        $statement->bind_param('i', $expenseId);
        $statement->execute();
        $expense = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($expense === null) {
            return null;
        }

        $this->expenseId = (int) $expense['expense_id'];

        return $expense;
    }

    public function updateExpense(int $expenseId, string $category, float $amount, string $expenseDate, string $notes): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE expenses SET category=?, amount=?, expense_date=?, notes=? WHERE expense_id = ?'
        );
        $statement->bind_param('sdssi', $category, $amount, $expenseDate, $notes, $expenseId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteExpense(int $expenseId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM expenses WHERE expense_id = ?');
        $statement->bind_param('i', $expenseId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllExpenses(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM expenses ORDER BY expense_date DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
