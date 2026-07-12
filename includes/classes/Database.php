<?php

/**
 * Provides the application's shared MySQLi connection.
 */
class Database
{
    private string $host;

    private string $username;

    private string $password;

    private string $database;

    private mysqli $connection;

    public function __construct(
        string $host,
        string $username,
        string $password,
        string $database
    ) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;

        $this->connection = new mysqli(
            $this->host,
            $this->username,
            $this->password,
            $this->database
        );

        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }
    }

    /**
     * Returns the existing MySQLi connection for use by module classes.
     */
    public function getConnection(): mysqli
    {
        return $this->connection;
    }
}
