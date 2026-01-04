<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entité Commande représentant la commande du diagramme UML
 */
#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Commande
{
    use TimestampableTrait;

    // Statuts de commande
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, name: "Datecommande")]
    #[Assert\NotNull(message: "La date de commande est obligatoire")]
    private ?\DateTime $datecommande = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, name: "prixtotale")]
    #[Assert\NotNull(message: "Le prix total est obligatoire")]
    #[Assert\PositiveOrZero(message: "Le prix total doit être positif ou zéro")]
    private ?string $prixtotal = null;

    #[ORM\Column(length: 50, options: ['default' => self::STATUS_PENDING])]
    #[Assert\Choice(
        choices: [self::STATUS_PENDING, self::STATUS_CONFIRMED, self::STATUS_PROCESSING, 
                  self::STATUS_SHIPPED, self::STATUS_DELIVERED, self::STATUS_CANCELLED],
        message: "Le statut de commande n'est pas valide"
    )]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $numeroCommande = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private ?Facture $facture = null;

    /**
     * @var Collection<int, CommandeProduit>
     */
    #[ORM\OneToMany(targetEntity: CommandeProduit::class, mappedBy: 'commande', cascade: ['persist', 'remove'])]
    private Collection $commandeProduits;

    public function __construct()
    {
        $this->commandeProduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatecommande(): ?\DateTime
    {
        return $this->datecommande;
    }

    public function setDatecommande(\DateTime $datecommande): static
    {
        $this->datecommande = $datecommande;

        return $this;
    }

    public function getPrixtotal(): ?string
    {
        return $this->prixtotal;
    }

    public function setPrixtotal(string|float $prixtotal): static
    {
        $this->prixtotal = (string) $prixtotal;

        return $this;
    }

    public function getPrixtotalAsFloat(): float
    {
        return (float) $this->prixtotal;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNumeroCommande(): ?string
    {
        return $this->numeroCommande;
    }

    public function setNumeroCommande(?string $numeroCommande): static
    {
        $this->numeroCommande = $numeroCommande;

        return $this;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED], true);
    }

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(Facture $facture): static
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * @return Collection<int, CommandeProduit>
     */
    public function getCommandeProduits(): Collection
    {
        return $this->commandeProduits;
    }

    public function addCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if (!$this->commandeProduits->contains($commandeProduit)) {
            $this->commandeProduits->add($commandeProduit);
            $commandeProduit->setCommande($this);
        }

        return $this;
    }

    public function removeCommandeProduit(CommandeProduit $commandeProduit): static
    {
        if ($this->commandeProduits->removeElement($commandeProduit)) {
            // set the owning side to null (unless already changed)
            if ($commandeProduit->getCommande() === $this) {
                $commandeProduit->setCommande(null);
            }
        }

        return $this;
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

    public function genererFacture(): Facture
    {
        $facture = new Facture();
        $facture->setCommande($this);
        $this->setFacture($facture);
        return $facture;
    }

    #[ORM\PrePersist]
    public function initializeCommande(): void
    {
        if ($this->datecommande === null) {
            $this->datecommande = new \DateTime();
        }
    }

    /**
     * Générer le numéro de commande après avoir obtenu l'ID
     */
    public function generateNumeroCommande(): void
    {
        if ($this->numeroCommande === null && $this->id !== null) {
            $this->numeroCommande = 'CMD-' . date('Ymd') . '-' . str_pad((string)$this->id, 6, '0', STR_PAD_LEFT);
        }
    }
}
