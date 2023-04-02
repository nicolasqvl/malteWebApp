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
}
