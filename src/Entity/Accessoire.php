<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Accessoire extends Produit
{
    #[ORM\Column(length: 50)]
    private ?string $accessoireType = null;

    public function getAccessoireType(): ?string
    {
        return $this->accessoireType;
    }

    public function setAccessoireType(string $accessoireType): static
    {
        $this->accessoireType = $accessoireType;
        return $this;
    }
}
