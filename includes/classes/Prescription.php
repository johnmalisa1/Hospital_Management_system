<?php

require_once __DIR__ . '/Database.php';

class Prescription
{
    private Database $database;
    private ?int $prescriptionId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addPrescription(int $patientId, int $doctorId, int $medicineId, string $dosage, string $instructions, string $dateIssued): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO prescriptions (patient_id, doctor_id, medicine_id, dosage, instructions, date_issued) VALUES (?, ?, ?, ?, ?, ?)');
        $statement->bind_param('iiisss', $patientId, $doctorId, $medicineId, $dosage, $instructions, $dateIssued);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getPrescriptionById(int $prescriptionId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM prescriptions WHERE prescription_id = ?');
        $statement->bind_param('i', $prescriptionId);
        $statement->execute();
        $prescription = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->prescriptionId = $prescription === null ? null : (int) $prescription['prescription_id'];
        return $prescription;
    }

    public function updatePrescription(int $prescriptionId, int $patientId, int $doctorId, int $medicineId, string $dosage, string $instructions, string $dateIssued): bool
    {
        $statement = $this->connection()->prepare('UPDATE prescriptions SET patient_id = ?, doctor_id = ?, medicine_id = ?, dosage = ?, instructions = ?, date_issued = ? WHERE prescription_id = ?');
        $statement->bind_param('iiisssi', $patientId, $doctorId, $medicineId, $dosage, $instructions, $dateIssued, $prescriptionId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deletePrescription(int $prescriptionId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM prescriptions WHERE prescription_id = ?');
        $statement->bind_param('i', $prescriptionId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getAllPrescriptions(): mysqli_result
    {
        return $this->connection()->query('SELECT p.*, pt.name AS patient_name, m.name AS medicine_name, u.username AS doctor_name FROM prescriptions p JOIN patients pt ON p.patient_id = pt.patient_id JOIN medicines m ON p.medicine_id = m.medicine_id JOIN users u ON p.doctor_id = u.id ORDER BY p.prescription_id DESC');
    }

    public function getPrescriptionsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT p.*, pt.name AS patient_name, m.name AS medicine_name FROM prescriptions p JOIN patients pt ON p.patient_id = pt.patient_id JOIN medicines m ON p.medicine_id = m.medicine_id WHERE p.doctor_id = ? ORDER BY p.date_issued DESC');
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getPrescriptionsByPatient(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT p.*, m.name AS medicine_name, u.username AS doctor_name FROM prescriptions p JOIN medicines m ON p.medicine_id = m.medicine_id JOIN users u ON p.doctor_id = u.id WHERE p.patient_id = ? ORDER BY p.date_issued DESC');
        $statement->bind_param('i', $patientId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getMedicines(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM medicines');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
