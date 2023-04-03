<?php

namespace App\Entity;

use App\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnitRepository::class)]
class Unit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'unit', targetEntity: User::class, orphanRemoval: true)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'unit', targetEntity: Team::class, orphanRemoval: true)]
    private Collection $teams;

    #[ORM\OneToMany(mappedBy: 'unit', targetEntity: Kit::class, orphanRemoval: true)]
    private Collection $kits;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->teams = new ArrayCollection();
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

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setUnit($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUnit() === $this) {
                $user->setUnit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setUnit($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getUnit() === $this) {
                $team->setUnit(null);
            }
        }

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
            $kit->setUnit($this);
        }

        return $this;
    }

    public function removeKit(Kit $kit): self
    {
        if ($this->kits->removeElement($kit)) {
            // set the owning side to null (unless already changed)
            if ($kit->getUnit() === $this) {
                $kit->setUnit(null);
            }
        }

        return $this;
    }
}
