<?php

namespace App\Service;

use App\Entity\Produit;
use App\Entity\Promotion;
use App\Repository\ProduitRepository;

/**
 * Service pour gérer la logique métier des produits
 */
class ProductService
{
    public function __construct(
        private ProduitRepository $produitRepository
    ) {
    }

    /**
     * Calculer le prix final d'un produit avec promotions
     */
    public function calculateFinalPrice(Produit $produit): float
    {
        $prix = (float) $produit->getPrix();
        
        // Récupérer les promotions actives
        $promotionsActives = $this->getActivePromotions($produit);
        
        if (!$promotionsActives->isEmpty()) {
            // Prendre la première promotion active (ou la meilleure)
            $promotion = $promotionsActives->first();
            $prix = $promotion->appliquerPromo($prix);
        }
        
        return round($prix, 2);
    }

    /**
     * Récupérer les promotions actives pour un produit
     */
    public function getActivePromotions(Produit $produit): \Doctrine\Common\Collections\Collection
    {
        $now = new \DateTime();
        
        return $produit->getPromotions()->filter(
            function (Promotion $promotion) use ($now) {
                return $promotion->getDateExpiration() >= $now;
            }
        );
    }

    /**
     * Vérifier si un produit est disponible
     */
    public function isAvailable(Produit $produit, int $quantite = 1): bool
    {
        return $produit->isEnStock() && $produit->getStock() >= $quantite;
    }

    /**
     * Réduire le stock d'un produit
     */
    public function reduceStock(Produit $produit, int $quantite): void
    {
        $stockActuel = $produit->getStock();
        
        if ($stockActuel < $quantite) {
            throw new \RuntimeException(
                sprintf(
                    'Stock insuffisant pour le produit %s. Stock disponible: %d, demandé: %d',
                    $produit->getNomProduit(),
                    $stockActuel,
                    $quantite
                )
            );
        }
        
        $nouveauStock = $stockActuel - $quantite;
        $produit->setStock($nouveauStock);
        
        if ($nouveauStock === 0) {
            $produit->setEnStock(false);
        }
    }

    /**
     * Augmenter le stock d'un produit
     */
    public function increaseStock(Produit $produit, int $quantite): void
    {
        $stockActuel = $produit->getStock();
        $produit->setStock($stockActuel + $quantite);
        $produit->setEnStock(true);
    }

    /**
     * Récupérer les produits en promotion
     */
    public function getProductsOnSale(): array
    {
        $produits = $this->produitRepository->findAll();
        $produitsEnPromo = [];
        
        foreach ($produits as $produit) {
            $promotions = $this->getActivePromotions($produit);
            if (!$promotions->isEmpty()) {
                $produitsEnPromo[] = $produit;
            }
        }
        
        return $produitsEnPromo;
    }

    /**
     * Récupérer les produits en rupture de stock
     */
    public function getOutOfStockProducts(): array
    {
        return $this->produitRepository->createQueryBuilder('p')
            ->where('p.stock = 0 OR p.enStock = false')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupérer les produits avec stock faible
     */
    public function getLowStockProducts(int $seuil = 10): array
    {
        return $this->produitRepository->createQueryBuilder('p')
            ->where('p.stock > 0')
            ->andWhere('p.stock <= :seuil')
            ->setParameter('seuil', $seuil)
            ->getQuery()
            ->getResult();
    }
}
