<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Vetement extends Produit
{
    #[ORM\Column(length: 50)]
    private ?string $taille = null;

    #[ORM\Column(length: 50)]
    private ?string $couleur = null;

    #[ORM\Column(length: 50)]
    private ?string $genre = null;

    // getters et setters
    public function getTaille(): ?string { return $this->taille; }
    public function setTaille(string $taille): static { $this->taille = $taille; return $this; }
    public function getCouleur(): ?string { return $this->couleur; }
    public function setCouleur(string $couleur): static { $this->couleur = $couleur; return $this; }
    public function getGenre(): ?string { return $this->genre; }
    public function setGenre(string $genre): static { $this->genre = $genre; return $this; }
}
