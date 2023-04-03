<?php

namespace App\Entity;

use App\Repository\KitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'kit', targetEntity: KitProduct::class, orphanRemoval: true)]
    private Collection $kitProducts;

    #[ORM\ManyToOne(inversedBy: 'kits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unit $unit = null;

    #[ORM\OneToMany(mappedBy: 'kit', targetEntity: Order::class, orphanRemoval: true)]
    private Collection $orders;

    #[ORM\ManyToOne(inversedBy: 'kits')]
    private ?Team $team = null;

    public function __construct()
    {
        $this->kitProducts = new ArrayCollection();
        $this->orders = new ArrayCollection();
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

    /**
     * @return Collection<int, KitProduct>
     */
    public function getKitProducts(): Collection
    {
        return $this->kitProducts;
    }

    public function addKitProduct(KitProduct $kitProduct): self
    {
        if (!$this->kitProducts->contains($kitProduct)) {
            $this->kitProducts->add($kitProduct);
            $kitProduct->setKit($this);
        }

        return $this;
    }

    public function removeKitProduct(KitProduct $kitProduct): self
    {
        if ($this->kitProducts->removeElement($kitProduct)) {
            // set the owning side to null (unless already changed)
            if ($kitProduct->getKit() === $this) {
                $kitProduct->setKit(null);
            }
        }

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
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setKit($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getKit() === $this) {
                $order->setKit(null);
            }
        }

        return $this;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }
}
