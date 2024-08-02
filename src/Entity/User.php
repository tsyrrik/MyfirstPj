<?php

namespace Entity;

class User
{
    private int $id;
    private string $name;
    private ?string $lastName;
    private string $email;
    private string $password;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->lastName = $data['last_name'] ?? null;
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}

