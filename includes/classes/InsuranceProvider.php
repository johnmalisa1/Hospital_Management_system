<?php

require_once __DIR__ . '/Database.php';

class InsuranceProvider
{
    private Database $database;
    private ?int $providerId = null;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function addProvider(string $providerName, string $contact): bool
    {
        $statement = $this->connection()->prepare(
            'INSERT INTO insurance_providers (provider_name, contact) VALUES (?, ?)'
        );
        $statement->bind_param('ss', $providerName, $contact);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getProviderById(int $providerId): ?array
    {
        $statement = $this->connection()->prepare(
            'SELECT * FROM insurance_providers WHERE provider_id = ?'
        );
        $statement->bind_param('i', $providerId);
        $statement->execute();
        $provider = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($provider === null) {
            return null;
        }

        $this->providerId = (int) $provider['provider_id'];

        return $provider;
    }

    public function updateProvider(int $providerId, string $providerName, string $contact): bool
    {
        $statement = $this->connection()->prepare(
            'UPDATE insurance_providers SET provider_name = ?, contact = ? WHERE provider_id = ?'
        );
        $statement->bind_param('ssi', $providerName, $contact, $providerId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function deleteProvider(int $providerId): bool
    {
        $statement = $this->connection()->prepare(
            'DELETE FROM insurance_providers WHERE provider_id = ?'
        );
        $statement->bind_param('i', $providerId);
        $result = $statement->execute();
        $statement->close();

        return $result;
    }

    public function getAllProviders(): mysqli_result
    {
        return $this->connection()->query('SELECT * FROM insurance_providers ORDER BY provider_id DESC');
    }

    private function connection(): mysqli
    {
        return $this->database->getConnection();
    }
}
