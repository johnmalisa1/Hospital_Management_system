<?php

require_once __DIR__ . '/User.php';

/**
 * Encapsulates patient persistence operations.
 */
class Patient extends User
{
    private ?int $patientId = null;

    private string $name = '';

    private string $gender = '';

    private string $dateOfBirth = '';

    private string $phone = '';

    private string $address = '';

    public function addPatient(
        string $name,
        string $gender,
        string $dateOfBirth,
        string $phone,
        string $address
    ): bool {
        $statement = $this->getConnection()->prepare(
            'INSERT INTO patients (name, gender, dob, phone, address) VALUES (?, ?, ?, ?, ?)'
        );
        $statement->bind_param('sssss', $name, $gender, $dateOfBirth, $phone, $address);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPatientById(int $patientId): ?array
    {
        $statement = $this->getConnection()->prepare(
            'SELECT * FROM patients WHERE patient_id = ?'
        );
        $statement->bind_param('i', $patientId);
        $statement->execute();
        $patient = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($patient === null) {
            return null;
        }

        $this->hydratePatient($patient);

        return $patient;
    }

    public function updatePatient(
        int $patientId,
        string $name,
        string $gender,
        string $dateOfBirth,
        string $phone,
        string $address
    ): bool {
        $statement = $this->getConnection()->prepare(
            'UPDATE patients SET name = ?, gender = ?, dob = ?, phone = ?, address = ? WHERE patient_id = ?'
        );
        $statement->bind_param(
            'sssssi',
            $name,
            $gender,
            $dateOfBirth,
            $phone,
            $address,
            $patientId
        );
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllPatients(): mysqli_result
    {
        return $this->getConnection()->query('SELECT * FROM patients ORDER BY patient_id DESC');
    }

    public function getPatientsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->getConnection()->prepare(
            'SELECT DISTINCT p.* FROM patients p '
            . 'JOIN appointments a ON p.patient_id = a.patient_id '
            . 'WHERE a.doctor_id = ? '
            . 'ORDER BY p.name ASC'
        );
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    public function countPatients(): int
    {
        $result = $this->getConnection()->query('SELECT COUNT(*) AS total FROM patients');
        $row = $result->fetch_assoc();

        return (int) $row['total'];
    }

    public function getMonthlyPatientCounts(): mysqli_result
    {
        return $this->getConnection()->query(
            "SELECT DATE_FORMAT(created_at, '%b') AS month, COUNT(*) AS total\n"
            . "FROM patients\n"
            . "GROUP BY month\n"
            . "ORDER BY STR_TO_DATE(month, '%b') ASC"
        );
    }

    public function getPatientId(): ?int
    {
        return $this->patientId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDisplayType(): string
    {
        return 'Patient';
    }

    /**
     * @param array<string, mixed> $patient
     */
    private function hydratePatient(array $patient): void
    {
        $this->patientId = (int) $patient['patient_id'];
        $this->name = (string) $patient['name'];
        $this->gender = (string) $patient['gender'];
        $this->dateOfBirth = (string) $patient['dob'];
        $this->phone = (string) $patient['phone'];
        $this->address = (string) $patient['address'];
    }
}
