<?php

require_once __DIR__ . '/Database.php';

class User
{
    protected Database $database;

    public function __construct(Database $database) { $this->database = $database; }

    public function getDoctorUserAccounts(): mysqli_result
    {
        return $this->getConnection()->query("SELECT id, username FROM users WHERE role = 'Doctor'");
    }

    public function getPatientIdByUsername(string $username): ?int
    {
        $statement = $this->getConnection()->prepare('SELECT patient_id FROM patients WHERE username = ?');
        $statement->bind_param('s', $username);
        $statement->execute();
        $patient = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $patient === null ? null : (int) $patient['patient_id'];
    }

    public function getByUsernameAndRole(string $username, string $role): ?array
    {
        $statement = $this->getConnection()->prepare('SELECT * FROM users WHERE username = ? AND role = ?');
        $statement->bind_param('ss', $username, $role);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        $statement->close();
        return $result ?: null;
    }

    protected function getConnection(): mysqli { return $this->database->getConnection(); }
}
