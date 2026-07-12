<?php

require_once __DIR__ . '/Database.php';

class LabTest
{
    private Database $database;
    private ?int $testId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addLabTest(string $testName, float $cost): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO lab_tests (test_name, cost) VALUES (?, ?)');
        $statement->bind_param('sd', $testName, $cost);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getLabTestById(int $testId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM lab_tests WHERE test_id = ?');
        $statement->bind_param('i', $testId);
        $statement->execute();
        $test = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->testId = $test === null ? null : (int) $test['test_id'];
        return $test;
    }

    public function updateLabTest(int $testId, string $testName, float $cost): bool
    {
        $statement = $this->connection()->prepare('UPDATE lab_tests SET test_name = ?, cost = ? WHERE test_id = ?');
        $statement->bind_param('sdi', $testName, $cost, $testId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deleteLabTest(int $testId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM lab_tests WHERE test_id = ?');
        $statement->bind_param('i', $testId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getLabTests(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM lab_tests ORDER BY test_id DESC');
    }

    public function getAllLabTests(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM lab_tests');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
