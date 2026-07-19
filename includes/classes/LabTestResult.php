<?php

require_once __DIR__ . '/Database.php';

class LabTestResult
{
    private Database $database;
    private ?int $resultId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addResult(int $patientId, int $testId, int $doctorId, string $resultText, string $resultDate): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO lab_test_results (patient_id, test_id, doctor_id, result_text, result_date) VALUES (?, ?, ?, ?, ?)');
        $statement->bind_param('iiiss', $patientId, $testId, $doctorId, $resultText, $resultDate);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getResultById(int $resultId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM lab_test_results WHERE result_id = ?');
        $statement->bind_param('i', $resultId);
        $statement->execute();
        $result = $statement->get_result()->fetch_assoc();
        $statement->close();
        $this->resultId = $result === null ? null : (int) $result['result_id'];
        return $result;
    }

    public function updateResult(int $resultId, string $resultText, string $resultDate): bool
    {
        $statement = $this->connection()->prepare('UPDATE lab_test_results SET result_text = ?, result_date = ? WHERE result_id = ?');
        $statement->bind_param('ssi', $resultText, $resultDate, $resultId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function deleteResult(int $resultId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM lab_test_results WHERE result_id = ?');
        $statement->bind_param('i', $resultId);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getAllResults(): mysqli_result
    {
        return $this->connection()->query('SELECT r.*, p.name AS patient_name, t.test_name, u.username AS doctor_name FROM lab_test_results r JOIN patients p ON r.patient_id = p.patient_id JOIN lab_tests t ON r.test_id = t.test_id JOIN users u ON r.doctor_id = u.id');
    }

    public function getResultsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT r.*, p.name AS patient_name, t.test_name FROM lab_test_results r JOIN patients p ON r.patient_id = p.patient_id JOIN lab_tests t ON r.test_id = t.test_id WHERE r.doctor_id = ? ORDER BY r.result_date DESC');
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getResultsByPatient(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT r.*, t.test_name, u.username AS doctor_name FROM lab_test_results r JOIN lab_tests t ON r.test_id = t.test_id JOIN users u ON r.doctor_id = u.id WHERE r.patient_id = ? ORDER BY r.result_date DESC');
        $statement->bind_param('i', $patientId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getPatientDashboardResults(int $patientId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT l.result_text, l.result_date, t.test_name FROM lab_test_results l JOIN lab_tests t ON l.test_id = t.test_id WHERE l.patient_id = ? ORDER BY l.result_date DESC');
        $statement->bind_param('i', $patientId);
        $statement->execute();
        return $statement->get_result();
    }

    public function requestLabTest(int $patientId, int $testId, int $doctorId): bool
    {
        $resultText = 'Pending';
        $statement = $this->connection()->prepare('INSERT INTO lab_test_results (patient_id, test_id, doctor_id, result_text, result_date) VALUES (?, ?, ?, ?, CURDATE())');
        $statement->bind_param('iiis', $patientId, $testId, $doctorId, $resultText);
        $result = $statement->execute();
        $statement->close();
        return $result;
    }

    public function getRequestedTestsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->connection()->prepare('SELECT r.*, p.name AS patient_name, t.test_name FROM lab_test_results r JOIN patients p ON r.patient_id = p.patient_id JOIN lab_tests t ON r.test_id = t.test_id WHERE r.doctor_id = ? AND r.result_text = \'Pending\' ORDER BY r.result_date DESC');
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
