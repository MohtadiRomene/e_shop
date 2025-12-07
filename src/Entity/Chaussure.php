<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Chaussure extends Produit
{
    #[ORM\Column]
    private ?int $pointure = null;

    public function getPointure(): ?int { return $this->pointure; }
    public function setPointure(int $pointure): static { $this->pointure = $pointure; return $this; }
}
