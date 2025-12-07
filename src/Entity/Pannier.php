<?php

namespace App\Entity;

use App\Repository\PannierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PannierRepository::class)]
class Pannier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Commande $commande = null;

    /**
     * @var Collection<int, PanierProduit>
     */
    #[ORM\OneToMany(targetEntity: PanierProduit::class, mappedBy: 'panier', cascade: ['persist', 'remove'])]
    private Collection $panierProduits;

    public function __construct()
    {
        $this->panierProduits = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * @return Collection<int, PanierProduit>
     */
    public function getPanierProduits(): Collection
    {
        return $this->panierProduits;
    }

    public function addPanierProduit(PanierProduit $panierProduit): static
    {
        if (!$this->panierProduits->contains($panierProduit)) {
            $this->panierProduits->add($panierProduit);
            $panierProduit->setPanier($this);
        }

        return $this;
    }

    public function removePanierProduit(PanierProduit $panierProduit): static
    {
        if ($this->panierProduits->removeElement($panierProduit)) {
            // set the owning side to null (unless already changed)
            if ($panierProduit->getPanier() === $this) {
                $panierProduit->setPanier(null);
            }
        }

        return $this;
    }

    public function calculerTotal(): float
    {
        $total = 0.0;
        foreach ($this->panierProduits as $panierProduit) {
            $total += $panierProduit->getQuantite() * $panierProduit->getPrixUnitaire();
        }
        return $total;
    }

    public function ajouterProduit(Produit $produit, int $quantite = 1): static
    {
        // Vérifier si le produit existe déjà dans le panier
        foreach ($this->panierProduits as $panierProduit) {
            if ($panierProduit->getProduit() === $produit) {
                $panierProduit->setQuantite($panierProduit->getQuantite() + $quantite);
                return $this;
            }
        }

        // Créer un nouveau PanierProduit
        $panierProduit = new PanierProduit();
        $panierProduit->setProduit($produit);
        $panierProduit->setQuantite($quantite);
        $panierProduit->setPrixUnitaire($produit->getPrix());
        $this->addPanierProduit($panierProduit);

        return $this;
    }

    public function supprimerProduit(Produit $produit): static
    {
        foreach ($this->panierProduits as $panierProduit) {
            if ($panierProduit->getProduit() === $produit) {
                $this->removePanierProduit($panierProduit);
                break;
            }
        }

        return $this;
    }

    public function passerCommande(): Commande
    {
        $commande = new Commande();
        $commande->setUser($this->user);
        $commande->setDatecommande(new \DateTime());
        $commande->setPrixtotal($this->calculerTotal());

        // Copier les produits du panier vers la commande
        foreach ($this->panierProduits as $panierProduit) {
            $commandeProduit = new CommandeProduit();
            $commandeProduit->setProduit($panierProduit->getProduit());
            $commandeProduit->setQuantite($panierProduit->getQuantite());
            $commandeProduit->setPrixUnitaire($panierProduit->getPrixUnitaire());
            $commande->addCommandeProduit($commandeProduit);
        }

        $this->setCommande($commande);

        return $commande;
    }
}
