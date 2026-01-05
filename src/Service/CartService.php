<?php

namespace App\Service;

use App\Entity\Pannier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Repository\PannierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Service pour gérer la logique métier du panier
 */
class CartService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private PannierRepository $panierRepository,
        private Security $security
    ) {
    }

    /**
     * Récupérer ou créer le panier de l'utilisateur connecté
     */
    public function getOrCreateCart(): Pannier
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            throw new \RuntimeException('L\'utilisateur doit être connecté pour accéder au panier');
        }

        $panier = $this->panierRepository->findOneBy(['user' => $user]);
        
        if (!$panier) {
            $panier = new Pannier();
            $panier->setUser($user);
            $this->entityManager->persist($panier);
            $this->entityManager->flush();
        }

        return $panier;
    }

    /**
     * Ajouter un produit au panier
     */
    public function addProductToCart(Produit $produit, int $quantite = 1): Pannier
    {
        if ($quantite <= 0) {
            throw new BadRequestHttpException('La quantité doit être positive');
        }

        if (!$produit->isEnStock()) {
            throw new BadRequestHttpException('Le produit n\'est plus en stock');
        }

        if ($quantite > $produit->getStock()) {
            throw new BadRequestHttpException('Quantité demandée supérieure au stock disponible');
        }

        $panier = $this->getOrCreateCart();
        
        // Vérifier si le produit existe déjà dans le panier
        $panierProduit = $this->findProductInCart($panier, $produit);
        
        if ($panierProduit) {
            // Mettre à jour la quantité
            $nouvelleQuantite = $panierProduit->getQuantite() + $quantite;
            
            if ($nouvelleQuantite > $produit->getStock()) {
                throw new BadRequestHttpException('Quantité totale supérieure au stock disponible');
            }
            
            $panierProduit->setQuantite($nouvelleQuantite);
        } else {
            // Créer un nouveau PanierProduit
            $panierProduit = new PanierProduit();
            $panierProduit->setProduit($produit);
            $panierProduit->setQuantite($quantite);
            $panierProduit->setPrixUnitaire($produit->getPrixAvecPromotion());
            $panierProduit->setPanier($panier);
            $panier->addPanierProduit($panierProduit);
        }

        $this->entityManager->flush();

        return $panier;
    }

    /**
     * Modifier la quantité d'un produit dans le panier
     */
    public function updateProductQuantity(PanierProduit $panierProduit, int $quantite): Pannier
    {
        $user = $this->security->getUser();
        $panier = $panierProduit->getPanier();

        if ($panier->getUser() !== $user) {
            throw new \RuntimeException('Ce panier ne vous appartient pas');
        }

        if ($quantite <= 0) {
            // Supprimer le produit du panier
            $this->entityManager->remove($panierProduit);
        } else {
            $produit = $panierProduit->getProduit();
            
            if (!$produit->isEnStock()) {
                throw new BadRequestHttpException('Le produit n\'est plus en stock');
            }

            if ($quantite > $produit->getStock()) {
                throw new BadRequestHttpException('Quantité supérieure au stock disponible');
            }

            $panierProduit->setQuantite($quantite);
            // Mettre à jour le prix en cas de changement de promotion
            $panierProduit->setPrixUnitaire($produit->getPrixAvecPromotion());
        }

        $this->entityManager->flush();

        return $panier;
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeProductFromCart(PanierProduit $panierProduit): Pannier
    {
        $user = $this->security->getUser();
        $panier = $panierProduit->getPanier();

        if ($panier->getUser() !== $user) {
            throw new \RuntimeException('Ce panier ne vous appartient pas');
        }

        $this->entityManager->remove($panierProduit);
        $this->entityManager->flush();

        return $panier;
    }

    /**
     * Vider le panier
     */
    public function clearCart(): Pannier
    {
        $panier = $this->getOrCreateCart();
        
        foreach ($panier->getPanierProduits() as $panierProduit) {
            $this->entityManager->remove($panierProduit);
        }
        
        $this->entityManager->flush();

        return $panier;
    }

    /**
     * Calculer le total du panier
     */
    public function calculateTotal(Pannier $panier): float
    {
        return $panier->calculerTotal();
    }

    /**
     * Vérifier la disponibilité des produits dans le panier
     */
    public function validateCart(Pannier $panier): array
    {
        $errors = [];
        
        foreach ($panier->getPanierProduits() as $panierProduit) {
            $produit = $panierProduit->getProduit();
            
            if (!$produit->isEnStock()) {
                $errors[] = sprintf(
                    'Le produit "%s" n\'est plus en stock',
                    $produit->getNomProduit()
                );
            }
            
            if ($panierProduit->getQuantite() > $produit->getStock()) {
                $errors[] = sprintf(
                    'La quantité demandée pour "%s" (%d) dépasse le stock disponible (%d)',
                    $produit->getNomProduit(),
                    $panierProduit->getQuantite(),
                    $produit->getStock()
                );
            }
        }
        
        return $errors;
    }

    /**
     * Trouver un produit dans le panier
     */
    private function findProductInCart(Pannier $panier, Produit $produit): ?PanierProduit
    {
        foreach ($panier->getPanierProduits() as $panierProduit) {
            if ($panierProduit->getProduit() === $produit) {
                return $panierProduit;
            }
        }
        
        return null;
    }
}

