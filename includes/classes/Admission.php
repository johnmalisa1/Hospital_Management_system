<?php

require_once __DIR__ . '/Database.php';

class Admission
{
    private Database $database;
    private ?int $admissionId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addAdmission(int $patientId, int $roomId, string $admissionDate): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO admissions (patient_id, room_id, admission_date) VALUES (?, ?, ?)'
        );
        $statement->bind_param('iis', $patientId, $roomId, $admissionDate);
        $result = $statement->execute();
        $statement->close();

        $this->connection()->query("UPDATE rooms SET is_available = 0 WHERE room_id = $roomId");

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAdmissionById(int $admissionId): ?array
    {
        $statement = $this->connection()->prepare(
            'SELECT * FROM admissions WHERE admission_id = ?'
        );
        $statement->bind_param('i', $admissionId);
        $statement->execute();
        $admission = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($admission === null) {
            return null;
        }

        $this->admissionId = (int) $admission['admission_id'];

        return $admission;
    }

    public function updateAdmission(int $admissionId, int $patientId, int $roomId, string $admissionDate): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE admissions SET patient_id=?, room_id=?, admission_date=? WHERE admission_id=?'
        );
        $statement->bind_param('iisi', $patientId, $roomId, $admissionDate, $admissionId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteAdmission(int $admissionId): bool
    {
        $statement = $this->connection()->prepare(
            'DELETE FROM admissions WHERE admission_id = ?'
        );
        $statement->bind_param('i', $admissionId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllAdmissions(): mysqli_result
    {
        return $this->connection()->query(
            'SELECT a.admission_id, p.name AS patient_name, r.room_number, a.admission_date
             FROM admissions a
             JOIN patients p ON a.patient_id = p.patient_id
             JOIN rooms r ON a.room_id = r.room_id
             ORDER BY a.admission_id DESC'
        );
    }

    public function getAvailableRooms(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM rooms WHERE is_available = 1');
    }

    public function getAllRooms(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM rooms');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
