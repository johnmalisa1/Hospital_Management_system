<?php

require_once __DIR__ . '/Database.php';

class Treatment
{
    private Database $database;
    private ?int $treatmentId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addTreatment(int $patientId, int $doctorId, string $description, string $dateGiven): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO treatments (patient_id, doctor_id, description, date_given) VALUES (?, ?, ?, ?)');
        $statement->bind_param('iiss', $patientId, $doctorId, $description, $dateGiven);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getTreatmentById(int $treatmentId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM treatments WHERE treatment_id = ?');
        $statement->bind_param('i', $treatmentId);
        $statement->execute();
        $treatment = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->treatmentId = $treatment === null ? null : (int) $treatment['treatment_id'];
        return $treatment;
    }

    public function updateTreatment(int $treatmentId, int $patientId, int $doctorId, string $description, string $dateGiven): bool
    {
        $statement = $this->connection()->prepare('UPDATE treatments SET patient_id = ?, doctor_id = ?, description = ?, date_given = ? WHERE treatment_id = ?');
        $statement->bind_param('iissi', $patientId, $doctorId, $description, $dateGiven, $treatmentId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deleteTreatment(int $treatmentId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM treatments WHERE treatment_id = ?');
        $statement->bind_param('i', $treatmentId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getAllTreatments(): mysqli_result
    {
        return $this->connection()->query('SELECT t.*, p.name AS patient_name, u.username AS doctor_name FROM treatments t JOIN patients p ON t.patient_id = p.patient_id JOIN users u ON t.doctor_id = u.id ORDER BY date_given DESC');
    }

    public function getTreatmentsByPatient(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT t.*, p.name AS patient_name, u.username AS doctor_name FROM treatments t JOIN patients p ON t.patient_id = p.patient_id JOIN users u ON t.doctor_id = u.id WHERE t.patient_id = ? ORDER BY t.date_given DESC');
        $statement->bind_param('i', $patientId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getTreatmentsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT t.*, p.name AS patient_name FROM treatments t JOIN patients p ON t.patient_id = p.patient_id WHERE t.doctor_id = ? ORDER BY t.date_given DESC');
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
