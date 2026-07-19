<?php

require_once __DIR__ . '/Database.php';

/**
 * Encapsulates appointment persistence operations.
 */
class Appointment
{
    private Database $database;

    private ?int $appointmentId = null;

    private ?int $patientId = null;

    private ?int $doctorId = null;

    private string $appointmentDate = '';

    private string $status = '';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function bookAppointment(int $patientId, int $doctorId, string $appointmentDate): bool
    {
        $statement = $this->getConnection()->prepare(
            "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, 'Scheduled')"
        );
        $statement->bind_param('iis', $patientId, $doctorId, $appointmentDate);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /** @return array<string, mixed>|null */
    public function getAppointmentById(int $appointmentId): ?array
    {
        $statement = $this->getConnection()->prepare(
            'SELECT * FROM appointments WHERE appointment_id = ?'
        );
        $statement->bind_param('i', $appointmentId);
        $statement->execute();
        $appointment = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($appointment === null) {
            return null;
        }

        $this->hydrate($appointment);

        return $appointment;
    }

    public function updateAppointment(int $appointmentId, int $patientId, int $doctorId, string $appointmentDate): bool
    {
        $statement = $this->getConnection()->prepare(
            'UPDATE appointments SET patient_id = ?, doctor_id = ?, appointment_date = ? WHERE appointment_id = ?'
        );
        $statement->bind_param('iisi', $patientId, $doctorId, $appointmentDate, $appointmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function rescheduleAppointment(int $appointmentId, string $appointmentDate): bool
    {
        $statement = $this->getConnection()->prepare(
            "UPDATE appointments SET appointment_date = ?, status = 'Rescheduled' WHERE appointment_id = ?"
        );
        $statement->bind_param('si', $appointmentDate, $appointmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function cancelAppointment(int $appointmentId, string $cancelledBy): bool
    {
        $status = 'Cancelled by ' . $cancelledBy;
        $statement = $this->getConnection()->prepare(
            'UPDATE appointments SET status = ? WHERE appointment_id = ?'
        );
        $statement->bind_param('si', $status, $appointmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function completeAppointment(int $appointmentId): bool
    {
        $statement = $this->getConnection()->prepare(
            "UPDATE appointments SET status = 'Completed' WHERE appointment_id = ?"
        );
        $statement->bind_param('i', $appointmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteAppointment(int $appointmentId): bool
    {
        $statement = $this->getConnection()->prepare('DELETE FROM appointments WHERE appointment_id = ?');
        $statement->bind_param('i', $appointmentId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllAppointments(): mysqli_result
    {
        return $this->getConnection()->query(
            'SELECT a.*, p.name AS patient_name, u.username AS doctor_name '
            . 'FROM appointments a '
            . 'JOIN patients p ON a.patient_id = p.patient_id '
            . 'JOIN users u ON a.doctor_id = u.id '
            . 'ORDER BY a.appointment_date DESC'
        );
    }

    public function getAppointmentsByPatient(int $patientId): mysqli_result
    {
        $statement = $this->getConnection()->prepare(
            'SELECT a.*, u.username AS doctor_name '
            . 'FROM appointments a '
            . 'JOIN users u ON a.doctor_id = u.id '
            . 'WHERE a.patient_id = ? '
            . 'ORDER BY a.appointment_date DESC'
        );
        $statement->bind_param('i', $patientId);
        $statement->execute();

        return $statement->get_result();
    }

    public function getAppointmentsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->getConnection()->prepare(
            'SELECT a.*, p.name AS patient_name '
            . 'FROM appointments a '
            . 'JOIN patients p ON a.patient_id = p.patient_id '
            . 'WHERE a.doctor_id = ? '
            . 'ORDER BY a.appointment_date DESC'
        );
        $statement->bind_param('i', $doctorId);
        $statement->execute();

        return $statement->get_result();
    }

    public function countAppointments(): int
    {
        $result = $this->getConnection()->query('SELECT COUNT(*) AS total FROM appointments');
        $row = $result->fetch_assoc();

        return (int) $row['total'];
    }

    public function countAppointmentsByDoctorAndStatus(int $doctorId, string $status): int
    {
        $statement = $this->getConnection()->prepare('SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND status = ?');
        $statement->bind_param('is', $doctorId, $status);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();
        return (int) $row['total'];
    }

    public function countTodayByDoctor(int $doctorId): int
    {
        $statement = $this->getConnection()->prepare("SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE()");
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();
        return (int) $row['total'];
    }

    public function countTodayByDoctorAndStatus(int $doctorId, string $status): int
    {
        $statement = $this->getConnection()->prepare("SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE() AND status = ?");
        $statement->bind_param('is', $doctorId, $status);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();
        return (int) $row['total'];
    }

    public function countTodayPatientsSeen(int $doctorId): int
    {
        $statement = $this->getConnection()->prepare("SELECT COUNT(DISTINCT patient_id) AS total FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE() AND status = 'Completed'");
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();
        return (int) $row['total'];
    }

    public function countTodayCancelledByDoctor(int $doctorId): int
    {
        $statement = $this->getConnection()->prepare("SELECT COUNT(*) AS total FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE() AND status LIKE '%Cancelled%'");
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        $row = $statement->get_result()->fetch_assoc();
        $statement->close();
        return (int) $row['total'];
    }

    public function getPatientsByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->getConnection()->prepare(
            'SELECT DISTINCT p.patient_id, p.name, p.gender, p.phone, a.appointment_date, a.status '
            . 'FROM appointments a '
            . 'JOIN patients p ON a.patient_id = p.patient_id '
            . 'WHERE a.doctor_id = ? '
            . 'ORDER BY a.appointment_date DESC'
        );
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getUpcomingByDoctor(int $doctorId): mysqli_result
    {
        $statement = $this->getConnection()->prepare(
            'SELECT a.*, p.name AS patient_name '
            . 'FROM appointments a '
            . 'JOIN patients p ON a.patient_id = p.patient_id '
            . 'WHERE a.doctor_id = ? AND a.appointment_date >= CURDATE() AND a.status IN ("Scheduled", "Rescheduled") '
            . 'ORDER BY a.appointment_date ASC'
        );
        $statement->bind_param('i', $doctorId);
        $statement->execute();
        return $statement->get_result();
    }

    public function getAppointmentId(): ?int
    {
        return $this->appointmentId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    private function getConnection(): mysqli
    {
        return $this->database->getConnection();
    }

    /** @param array<string, mixed> $appointment */
    private function hydrate(array $appointment): void
    {
        $this->appointmentId = (int) $appointment['appointment_id'];
        $this->patientId = (int) $appointment['patient_id'];
        $this->doctorId = (int) $appointment['doctor_id'];
        $this->appointmentDate = (string) $appointment['appointment_date'];
        $this->status = (string) $appointment['status'];
    }
}
