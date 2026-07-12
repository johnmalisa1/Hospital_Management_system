<?php

require_once __DIR__ . '/User.php';

/**
 * Encapsulates doctor persistence operations.
 */
class Doctor extends User
{
    private ?int $doctorId = null;

    private string $doctorName = '';

    private string $specialization = '';

    private string $phone = '';

    private ?int $departmentId = null;

    public function addDoctor(
        string $doctorName,
        string $specialization,
        string $phone,
        int $departmentId
    ): bool {
        $statement = $this->getConnection()->prepare(
            'INSERT INTO doctors (doctor_name, specialization, phone, department_id) VALUES (?, ?, ?, ?)'
        );
        $statement->bind_param('sssi', $doctorName, $specialization, $phone, $departmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDoctorById(int $doctorId): ?array
    {
        $statement = $this->getConnection()->prepare(
            'SELECT * FROM doctors WHERE doctor_id = ?'
        );
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        $doctor = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($doctor === null) {
            return null;
        }

        $this->hydrateDoctor($doctor);

        return $doctor;
    }

    public function updateDoctor(
        int $doctorId,
        string $doctorName,
        string $specialization,
        string $phone,
        int $departmentId
    ): bool {
        $statement = $this->getConnection()->prepare(
            'UPDATE doctors SET doctor_name = ?, specialization = ?, phone = ?, department_id = ? WHERE doctor_id = ?'
        );
        $statement->bind_param('sssii', $doctorName, $specialization, $phone, $departmentId, $doctorId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteDoctor(int $doctorId): bool
    {
        $statement = $this->getConnection()->prepare('DELETE FROM doctors WHERE doctor_id = ?');
        $statement->bind_param('i', $doctorId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllDoctors(): mysqli_result
    {
        return $this->getConnection()->query(
            'SELECT d.doctor_id, d.doctor_name, d.specialization, d.phone, dp.name AS department '
            . 'FROM doctors d '
            . 'LEFT JOIN departments dp ON d.department_id = dp.department_id '
            . 'ORDER BY d.doctor_id DESC'
        );
    }

    public function getAllDepartments(): mysqli_result
    {
        return $this->getConnection()->query('SELECT department_id, name FROM departments');
    }

    public function getDoctorId(): ?int
    {
        return $this->doctorId;
    }

    public function getDoctorName(): string
    {
        return $this->doctorName;
    }

    public function getDisplayType(): string
    {
        return 'Doctor';
    }

    private function getConnection(): mysqli
    {
        return $this->getDatabase()->getConnection();
    }

    /**
     * @param array<string, mixed> $doctor
     */
    private function hydrateDoctor(array $doctor): void
    {
        $this->doctorId = (int) $doctor['doctor_id'];
        $this->doctorName = (string) $doctor['doctor_name'];
        $this->specialization = (string) $doctor['specialization'];
        $this->phone = (string) $doctor['phone'];
        $this->departmentId = isset($doctor['department_id'])
            ? (int) $doctor['department_id']
            : null;
    }
}
