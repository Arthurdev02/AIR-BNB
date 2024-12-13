<?php

namespace App\Model\Entity;

use App\Model\Repository\RepoManager;
use Symplefony\Model\Entity;

class User extends Entity
{
    protected string $password;
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(int $value): self
    {
        $this->id = $value;
        return $this; // Permet de "chaîner" les appels aux setters: $toto->setId(2)->setName('toto'), etc.
    }

    protected string $email;
    public function getEmail(): string
    {
        return $this->email;
    }
    public function setEmail(int $value): self
    {
        $this->email = $value;
        return $this;
    }

    protected string $firstname;
    public function getFirstname(): string
    {
        return $this->firstname;
    }
    public function setFirstname(int $value): self
    {
        $this->firstname = $value;
        return $this;
    }

    protected string $lastname;
    public function getLastname(): string
    {
        return $this->lastname;
    }
    public function setLastname(int $value): self
    {
        $this->lastname = $value;
        return $this;
    }

    protected string $phone_number;
    public function getPhoneNumber(): string
    {
        return $this->phone_number;
    }
    public function setPhoneNumber(int $value): self
    {
        $this->phone_number = $value;
        return $this;
    }

    protected string $role_id;
    public function getRoleId(): string
    {
        return $this->role_id;
    }
    public function setRoleId(int $value): self
    {
        $this->role_id = $value;
        return $this;
    }
    /**
     * Rôle administrateur
     */
    public const ROLE_OWNER = 1;
    /**
     * Rôle commercial
     */
    public const ROLE_USER = 2;
    /**
     * Rôle client
     */
    public const ROLE_CUSTOMER = 3;
}
