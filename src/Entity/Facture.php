<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'facture', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function afficherFacture(): string
    {
        $commande = $this->commande;
        if (!$commande) {
            return 'Facture invalide';
        }

        return sprintf(
            "Facture N°%d\nCommande N°%d\nDate: %s\nTotal: %.2f €",
            $this->id,
            $commande->getId(),
            $commande->getDatecommande()?->format('d/m/Y') ?? 'N/A',
            $commande->getPrixtotal() ?? 0.0
        );
    }
}
