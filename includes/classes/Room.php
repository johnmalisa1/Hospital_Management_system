<?php

require_once __DIR__ . '/Database.php';

class Room
{
    private Database $database;
    private ?int $roomId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addRoom(string $roomNumber, int $isAvailable): bool
    {
        $statement = $this->connection()->prepare('INSERT INTO rooms (room_number, is_available) VALUES (?, ?)');
        $statement->bind_param('si', $roomNumber, $isAvailable);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRoomById(int $roomId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM rooms WHERE room_id = ?');
        $statement->bind_param('i', $roomId);
        $statement->execute();
        $room = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($room === null) {
            return null;
        }

        $this->roomId = (int) $room['room_id'];

        return $room;
    }

    public function updateRoom(int $roomId, string $roomNumber, int $isAvailable): bool
    {
        $statement = $this->connection()->prepare('UPDATE rooms SET room_number = ?, is_available = ? WHERE room_id = ?');
        $statement->bind_param('sii', $roomNumber, $isAvailable, $roomId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteRoom(int $roomId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM rooms WHERE room_id = ?');
        $statement->bind_param('i', $roomId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllRooms(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM rooms ORDER BY room_id DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
