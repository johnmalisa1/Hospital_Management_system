<?php

require_once __DIR__ . '/Database.php';

class Ambulance
{
    private Database $database;
    private ?int $ambulanceId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addAmbulance(string $vehicleNumber, string $driverName, string $contactNumber, string $availability): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO ambulances (vehicle_number, driver_name, contact_number, availability) VALUES (?, ?, ?, ?)'
        );
        $statement->bind_param('ssss', $vehicleNumber, $driverName, $contactNumber, $availability);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getAmbulanceById(int $ambulanceId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM ambulances WHERE ambulance_id = ?');
        $statement->bind_param('i', $ambulanceId);
        $statement->execute();
        $ambulance = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($ambulance === null) {
            return null;
        }

        $this->ambulanceId = (int) $ambulance['ambulance_id'];

        return $ambulance;
    }

    public function updateAmbulance(int $ambulanceId, string $vehicleNumber, string $driverName, string $contactNumber, string $availability): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE ambulances SET vehicle_number=?, driver_name=?, contact_number=?, availability=? WHERE ambulance_id = ?'
        );
        $statement->bind_param('ssssi', $vehicleNumber, $driverName, $contactNumber, $availability, $ambulanceId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteAmbulance(int $ambulanceId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM ambulances WHERE ambulance_id = ?');
        $statement->bind_param('i', $ambulanceId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllAmbulances(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM ambulances ORDER BY ambulance_id DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
