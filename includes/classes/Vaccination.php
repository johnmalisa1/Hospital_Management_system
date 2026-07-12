<?php

require_once __DIR__ . '/Database.php';

class Vaccination
{
    private Database $database;
    private ?int $vaccinationId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addVaccination(int $patientId, string $vaccineName, string $dateAdministered, string $notes): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO vaccinations (patient_id, vaccine_name, date_administered, notes) VALUES (?, ?, ?, ?)');
        $statement->bind_param('isss', $patientId, $vaccineName, $dateAdministered, $notes);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getVaccinationById(int $vaccinationId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM vaccinations WHERE vaccination_id = ?');
        $statement->bind_param('i', $vaccinationId);
        $statement->execute();
        $vaccination = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->vaccinationId = $vaccination === null ? null : (int) $vaccination['vaccination_id'];
        return $vaccination;
    }

    public function updateVaccination(int $vaccinationId, int $patientId, string $vaccineName, string $dateAdministered, string $notes): bool
    {
        $statement = $this->connection()->prepare('UPDATE vaccinations SET patient_id = ?, vaccine_name = ?, date_administered = ?, notes = ? WHERE vaccination_id = ?');
        $statement->bind_param('isssi', $patientId, $vaccineName, $dateAdministered, $notes, $vaccinationId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deleteVaccination(int $vaccinationId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM vaccinations WHERE vaccination_id = ?');
        $statement->bind_param('i', $vaccinationId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getAllVaccinations(): mysqli_result
    {
        return $this->connection()->query('SELECT v.*, p.name AS patient_name FROM vaccinations v JOIN patients p ON v.patient_id = p.patient_id');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
