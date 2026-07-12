<?php

require_once __DIR__ . '/Database.php';

class Department
{
    private Database $database;
    private ?int $departmentId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addDepartment(string $name): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO departments (name) VALUES (?)');
        $statement->bind_param('s', $name);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDepartmentById(int $departmentId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM departments WHERE department_id = ?');
        $statement->bind_param('i', $departmentId);
        $statement->execute();
        $department = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($department === null) {
            return null;
        }

        $this->departmentId = (int) $department['department_id'];

        return $department;
    }

    public function updateDepartment(int $departmentId, string $name): bool
    {
        $statement = $this->connection()->prepare('UPDATE departments SET name = ? WHERE department_id = ?');
        $statement->bind_param('si', $name, $departmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteDepartment(int $departmentId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM departments WHERE department_id = ?');
        $statement->bind_param('i', $departmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllDepartments(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM departments ORDER BY department_id DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
