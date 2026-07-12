<?php

require_once __DIR__ . '/Database.php';

class Notification
{
    private Database $database;
    private ?int $notificationId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addNotification(int $userId, string $message): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO notifications (user_id, message) VALUES (?, ?)'
        );
        $statement->bind_param('is', $userId, $message);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getNotificationById(int $notificationId): ?array
    {
        $statement = $this->connection()->prepare('SELECT * FROM notifications WHERE id = ?');
        $statement->bind_param('i', $notificationId);
        $statement->execute();
        $notification = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($notification === null) {
            return null;
        }

        $this->notificationId = (int) $notification['id'];

        return $notification;
    }

    public function updateNotification(int $notificationId, int $userId, string $message): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE notifications SET user_id=?, message=? WHERE id = ?'
        );
        $statement->bind_param('isi', $userId, $message, $notificationId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteNotification(int $notificationId): bool
    {
        $statement = $this->connection()->prepare('DELETE FROM notifications WHERE id = ?');
        $statement->bind_param('i', $notificationId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllNotifications(): mysqli_result
    {
        return $this->connection()->query(
            'SELECT n.*, u.username FROM notifications n JOIN users u ON n.user_id = u.id ORDER BY n.id DESC'
        );
    }

    public function getNotificationsByUserId(int $userId): mysqli_result
    {
        $statement = $this->connection()->prepare(
            'SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC'
        );
        $statement->bind_param('i', $userId);
        $statement->execute();
        return $statement->get_result();
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
