<?php

namespace App\Dto\Registration;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDto
{
    #[Assert\NotBlank]
    private string $username;
    #[Assert\NotBlank]
    private string $password;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }
}