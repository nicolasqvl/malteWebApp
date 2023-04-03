<?php

namespace App\Entity;

use App\Repository\KitProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KitProductRepository::class)]
class KitProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $productQuantity = null;

    #[ORM\Column]
    private ?int $productQuantityRequired = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unit $unit = null;

    #[ORM\ManyToOne(inversedBy: 'kitProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'kitProducts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Kit $kit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductQuantity(): ?int
    {
        return $this->productQuantity;
    }

    public function setProductQuantity(int $productQuantity): self
    {
        $this->productQuantity = $productQuantity;

        return $this;
    }

    public function getProductQuantityRequired(): ?int
    {
        return $this->productQuantityRequired;
    }

    public function setProductQuantityRequired(int $productQuantityRequired): self
    {
        $this->productQuantityRequired = $productQuantityRequired;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
