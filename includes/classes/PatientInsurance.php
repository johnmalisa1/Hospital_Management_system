<?php

require_once __DIR__ . '/Database.php';

class PatientInsurance
{
    private Database $database;
    private ?int $insuranceId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addInsurance(int $patientId, int $providerId, string $policyNumber, string $validUntil): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO patient_insurance (patient_id, provider_id, policy_number, valid_until) VALUES (?, ?, ?, ?)'
        );
        $statement->bind_param('iiss', $patientId, $providerId, $policyNumber, $validUntil);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getInsuranceById(int $insuranceId): ?array
    {
        $statement = $this->connection()->prepare(
            'SELECT * FROM patient_insurance WHERE id = ?'
        );
        $statement->bind_param('i', $insuranceId);
        $statement->execute();
        $insurance = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($insurance === null) {
            return null;
        }

        $this->insuranceId = (int) $insurance['id'];

        return $insurance;
    }

    public function updateInsurance(int $insuranceId, int $patientId, int $providerId, string $policyNumber, string $validUntil): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE patient_insurance SET patient_id = ?, provider_id = ?, policy_number = ?, valid_until = ? WHERE id = ?'
        );
        $statement->bind_param('iissi', $patientId, $providerId, $policyNumber, $validUntil, $insuranceId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteInsurance(int $insuranceId): bool
    {
        $statement = $this->connection()->prepare(
            'DELETE FROM patient_insurance WHERE id = ?'
        );
        $statement->bind_param('i', $insuranceId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllInsurance(): mysqli_result
    {
        return $this->connection()->query(
            'SELECT pi.*, p.name AS patient_name, ip.provider_name
             FROM patient_insurance pi
             JOIN patients p ON pi.patient_id = p.patient_id
             JOIN insurance_providers ip ON pi.provider_id = ip.provider_id
             ORDER BY pi.id DESC'
        );
    }

    public function getPatientInsurance(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare(
            'SELECT pi.*, p.name AS patient_name, ip.provider_name
             FROM patient_insurance pi
             JOIN patients p ON pi.patient_id = p.patient_id
             JOIN insurance_providers ip ON pi.provider_id = ip.provider_id
             WHERE pi.patient_id = ?'
        );
        $statement->bind_param('i', $patientId);
        $statement->execute();

        return $statement->get_result();
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
