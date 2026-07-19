<?php

require_once __DIR__ . '/Database.php';

class Diagnosis
{
    private Database $database;
    private ?int $diagnosisId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addDiagnosis(int $patientId, string $diagnosis, string $date, int $doctorId): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO diagnoses (patient_id, diagnosis, diagnosis_date, doctor_id) VALUES (?, ?, ?, ?)');
        $statement->bind_param('issi', $patientId, $diagnosis, $date, $doctorId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getDiagnosisById(int $diagnosisId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM diagnoses WHERE diagnosis_id = ?');
        $statement->bind_param('i', $diagnosisId);
        $statement->execute();
        $diagnosis = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->diagnosisId = $diagnosis === null ? null : (int) $diagnosis['diagnosis_id'];
        return $diagnosis;
    }

    public function updateDiagnosis(int $diagnosisId, int $patientId, string $diagnosis, string $date, int $doctorId): bool
    {
        $statement = $this->connection()->prepare('UPDATE diagnoses SET patient_id = ?, diagnosis = ?, diagnosis_date = ?, doctor_id = ? WHERE diagnosis_id = ?');
        $statement->bind_param('issii', $patientId, $diagnosis, $date, $doctorId, $diagnosisId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deleteDiagnosis(int $diagnosisId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM diagnoses WHERE diagnosis_id = ?');
        $statement->bind_param('i', $diagnosisId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getAllDiagnoses(): mysqli_result
    {
        return $this->connection()->query('SELECT d.*, p.name AS patient_name, u.username AS doctor_name FROM diagnoses d JOIN patients p ON d.patient_id = p.patient_id JOIN users u ON d.doctor_id = u.id');
    }

    public function getDiagnosesByPatient(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT d.*, p.name AS patient_name, u.username AS doctor_name FROM diagnoses d JOIN patients p ON d.patient_id = p.patient_id JOIN users u ON d.doctor_id = u.id WHERE d.patient_id = ? ORDER BY d.diagnosis_date DESC');
        $statement->bind_param('i', $patientId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getDiagnosesByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT d.*, p.name AS patient_name FROM diagnoses d JOIN patients p ON d.patient_id = p.patient_id WHERE d.doctor_id = ? ORDER BY d.diagnosis_date DESC');
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
