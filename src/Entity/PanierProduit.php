<?php

namespace App\Entity;

use App\Repository\PanierProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PanierProduitRepository::class)]
class PanierProduit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La quantité est obligatoire")]
    #[Assert\Positive(message: "La quantité doit être positive")]
    private ?int $quantite = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: "Le prix unitaire est obligatoire")]
    #[Assert\Positive(message: "Le prix unitaire doit être positif")]
    private ?string $prixUnitaire = null;

    #[ORM\ManyToOne(inversedBy: 'panierProduits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pannier $panier = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string|float $prixUnitaire): static
    {
        $this->prixUnitaire = (string) $prixUnitaire;

        return $this;
    }

    public function getPrixUnitaireAsFloat(): float
    {
        return (float) $this->prixUnitaire;
    }

    public function getTotal(): float
    {
        return $this->quantite * $this->getPrixUnitaireAsFloat();
    }

    public function getPanier(): ?Pannier
    {
        return $this->panier;
    }

    public function setPanier(?Pannier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}

