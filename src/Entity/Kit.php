<?php

namespace App\Entity;

use App\Repository\KitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KitRepository::class)]
class Kit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $originalUnit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getOriginalUnit(): ?string
    {
        return $this->originalUnit;
    }

    public function setOriginalUnit(?string $originalUnit): self
    {
        $this->originalUnit = $originalUnit;

        return $this;
    }
}
