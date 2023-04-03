<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unit $unit = null;

    #[ORM\OneToMany(mappedBy: 'team', targetEntity: Kit::class)]
    private Collection $kits;

    public function __construct()
    {
        $this->kits = new ArrayCollection();
    }

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

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(?Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Kit>
     */
    public function getKits(): Collection
    {
        return $this->kits;
    }

    public function addKit(Kit $kit): self
    {
        if (!$this->kits->contains($kit)) {
            $this->kits->add($kit);
            $kit->setTeam($this);
        }

        return $this;
    }

    public function removeKit(Kit $kit): self
    {
        if ($this->kits->removeElement($kit)) {
            // set the owning side to null (unless already changed)
            if ($kit->getTeam() === $this) {
                $kit->setTeam(null);
            }
        }

        return $this;
    }
}
