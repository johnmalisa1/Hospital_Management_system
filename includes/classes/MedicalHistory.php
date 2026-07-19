<?php

require_once __DIR__ . '/Database.php';

class MedicalHistory
{
    private Database $database;
    private ?int $historyId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addHistory(int $patientId, string $condition, string $treatment, string $dateRecorded, int $recordedBy): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO medical_history (patient_id, `condition`, treatment, date_recorded, recorded_by) VALUES (?, ?, ?, ?, ?)');
        $statement->bind_param('isssi', $patientId, $condition, $treatment, $dateRecorded, $recordedBy);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getHistoryById(int $historyId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM medical_history WHERE history_id = ?');
        $statement->bind_param('i', $historyId);
        $statement->execute();
        $history = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->historyId = $history === null ? null : (int) $history['history_id'];
        return $history;
    }

    public function updateHistory(int $historyId, int $patientId, string $condition, string $treatment, string $dateRecorded): bool
    {
        $statement = $this->connection()->prepare('UPDATE medical_history SET patient_id = ?, `condition` = ?, treatment = ?, date_recorded = ? WHERE history_id = ?');
        $statement->bind_param('isssi', $patientId, $condition, $treatment, $dateRecorded, $historyId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deleteHistory(int $historyId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM medical_history WHERE history_id = ?');
        $statement->bind_param('i', $historyId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getAllHistory(): mysqli_result
    {
        return $this->connection()->query('SELECT mh.*, p.name AS patient_name, u.username AS recorded_by_name FROM medical_history mh JOIN patients p ON mh.patient_id = p.patient_id LEFT JOIN users u ON mh.recorded_by = u.id ORDER BY mh.date_recorded DESC');
    }

    public function getHistoryByPatient(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT mh.*, p.name AS patient_name, u.username AS recorded_by_name FROM medical_history mh JOIN patients p ON mh.patient_id = p.patient_id LEFT JOIN users u ON mh.recorded_by = u.id WHERE mh.patient_id = ? ORDER BY mh.date_recorded DESC');
        $statement->bind_param('i', $patientId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getHistoryByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT mh.*, p.name AS patient_name FROM medical_history mh JOIN patients p ON mh.patient_id = p.patient_id WHERE mh.recorded_by = ? ORDER BY mh.date_recorded DESC');
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getHistoryId(): ?int
    {
        return $this->historyId;
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
