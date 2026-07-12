<?php

require_once __DIR__ . '/Person.php';
require_once __DIR__ . '/Database.php';

/**
 * Encapsulates system-user persistence and authentication lookups.
 */
class User extends Person
{
    private Database $database;

    private ?int $id = null;

    private string $role = '';

    private string $passwordHash = '';

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    /**
     * Finds one user with the supplied username and role.
     *
     * The query is intentionally equivalent to the original admin login query.
     *
     * @return array<string, mixed>|null
     */
    public function getByUsernameAndRole(string $username, string $role): ?array
    {
        $statement = $this->database->getConnection()->prepare(
            'SELECT * FROM users WHERE username = ? AND role = ?'
        );
        $statement->bind_param('ss', $username, $role);
        $statement->execute();

        $user = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($user === null) {
            return null;
        }

        $this->hydrate($user);

        return $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * Makes the injected database available to specialized user types.
     */
    protected function getDatabase(): Database
    {
        return $this->database;
    }

    /**
     * User is the base system-account type; child classes can override this.
     */
    public function getDisplayType(): string
    {
        return 'User';
    }

    /**
     * @param array<string, mixed> $user
     */
    private function hydrate(array $user): void
    {
        $this->id = (int) $user['id'];
        $this->setUsername((string) $user['username']);
        $this->role = (string) $user['role'];
        $this->passwordHash = (string) $user['password'];
    }
}
