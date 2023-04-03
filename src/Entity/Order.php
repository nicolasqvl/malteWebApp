<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $orderNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $declarerName = null;

    #[ORM\Column(length: 255)]
    private ?string $declarerPhone = null;

    #[ORM\Column]
    private ?bool $state = null;

    #[ORM\OneToMany(mappedBy: 'orderProduct', targetEntity: OrderDetail::class, orphanRemoval: true)]
    private Collection $orderDetails;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kit $kit = null;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderNumber(): ?string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDeclarerName(): ?string
    {
        return $this->declarerName;
    }

    public function setDeclarerName(string $declarerName): self
    {
        $this->declarerName = $declarerName;

        return $this;
    }

    public function getDeclarerPhone(): ?string
    {
        return $this->declarerPhone;
    }

    public function setDeclarerPhone(string $declarerPhone): self
    {
        $this->declarerPhone = $declarerPhone;

        return $this;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setOrderProduct($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrderProduct() === $this) {
                $orderDetail->setOrderProduct(null);
            }
        }

        return $this;
    }

    public function getKit(): ?Kit
    {
        return $this->kit;
    }

    public function setKit(?Kit $kit): self
    {
        $this->kit = $kit;

        return $this;
    }
}
