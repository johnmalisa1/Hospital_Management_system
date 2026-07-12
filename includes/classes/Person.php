<?php

/**
 * Common identity details shared by system people.
 */
abstract class Person
{
    protected string $username = '';

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Allows child types to describe themselves differently where needed.
     */
    abstract public function getDisplayType(): string;
}
