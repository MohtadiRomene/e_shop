<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\InheritanceType("SINGLE_TABLE")]
#[ORM\DiscriminatorColumn(name: "type", type: "string")]
#[ORM\DiscriminatorMap([
    "vetement" => Vetement::class,
    "chaussure" => Chaussure::class,
    "accessoire" => Accessoire::class
])]
class Produit
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom du produit est obligatoire")]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: "Le nom du produit doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom du produit ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $nomProduit = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: "Le prix est obligatoire")]
    #[Assert\Positive(message: "Le prix doit être positif")]
    #[Assert\GreaterThan(
        value: 0,
        message: "Le prix doit être supérieur à 0"
    )]
    private ?string $prix = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(message: "L'image doit être une URL valide")]
    private ?string $image = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(
        max: 1000,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères"
    )]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $enStock = true;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    #[Assert\PositiveOrZero(message: "Le stock doit être positif ou zéro")]
    private int $stock = 0;

    /**
     * @var Collection<int, Promotion>
     */
    #[ORM\ManyToMany(targetEntity: Promotion::class, mappedBy: 'produits')]
    private Collection $promotions;

    public function __construct()
    {
        $this->promotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(string $nomProduit): static
    {
        $this->nomProduit = $nomProduit;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string|float $prix): static
    {
        $this->prix = (string) $prix;
        return $this;
    }

    public function getPrixAsFloat(): float
    {
        return (float) $this->prix;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotions(): Collection
    {
        return $this->promotions;
    }

    public function addPromotion(Promotion $promotion): static
    {
        if (!$this->promotions->contains($promotion)) {
            $this->promotions->add($promotion);
            $promotion->addProduit($this);
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): static
    {
        if ($this->promotions->removeElement($promotion)) {
            $promotion->removeProduit($this);
        }

        return $this;
    }

    public function afficherDetails(): string
    {
        return sprintf(
            "Produit: %s - Prix: %.2f €",
            $this->nomProduit ?? '',
            $this->getPrixAsFloat()
        );
    }

    public function getType(): string
    {
        if ($this instanceof Vetement) {
            return 'vetement';
        } elseif ($this instanceof Chaussure) {
            return 'chaussure';
        } elseif ($this instanceof Accessoire) {
            return 'accessoire';
        }
        return 'produit';
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function isEnStock(): bool
    {
        return $this->enStock && $this->stock > 0;
    }

    public function setEnStock(bool $enStock): static
    {
        $this->enStock = $enStock;
        return $this;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;
        if ($stock <= 0) {
            $this->enStock = false;
        }
        return $this;
    }

    /**
     * Calculer le prix avec promotion si applicable
     */
    public function getPrixAvecPromotion(): float
    {
        $prix = $this->getPrixAsFloat();
        $promotionsActives = $this->promotions->filter(function($promotion) {
            return $promotion->getDateExpiration() >= new \DateTime();
        });

        if (!$promotionsActives->isEmpty()) {
            $promotion = $promotionsActives->first();
            $prix = $promotion->appliquerPromo($prix);
        }

        return $prix;
    }
}
