<?php

namespace App\Dto\Product;

use Symfony\Component\Validator\Constraints as Assert;

class CreateDto
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $name;
    #[Assert\NotBlank]
    #[Assert\Type("string")]
    private string $url;
    #[Assert\NotBlank]
    #[Assert\Type("float")]
    #[Assert\Positive]
    private float $price;
    #[Assert\NotBlank]
    #[Assert\Type("bool")]
    private bool $visible;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getVisible(): bool
    {
        return $this->visible;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;
        return $this;
    }
}