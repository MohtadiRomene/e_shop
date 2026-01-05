<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\Facture;
use App\Entity\Pannier;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Service pour gérer la logique métier des commandes
 */
class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommandeRepository $commandeRepository,
        private Security $security,
        private CartService $cartService,
        private ProductService $productService
    ) {
    }

    /**
     * Créer une commande à partir du panier
     */
    public function createOrderFromCart(Pannier $panier): Commande
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            throw new \RuntimeException('L\'utilisateur doit être connecté');
        }

        if ($panier->getUser() !== $user) {
            throw new \RuntimeException('Ce panier ne vous appartient pas');
        }

        if ($panier->getPanierProduits()->isEmpty()) {
            throw new BadRequestHttpException('Le panier est vide');
        }

        // Valider le panier
        $errors = $this->cartService->validateCart($panier);
        if (!empty($errors)) {
            throw new BadRequestHttpException(implode('. ', $errors));
        }

        // Créer la commande
        $commande = new Commande();
        $commande->setUser($user);
        $commande->setDatecommande(new \DateTime());
        $commande->setStatus(Commande::STATUS_PENDING);
        $commande->setPrixtotal((string) $this->cartService->calculateTotal($panier));

        // Le numéro de commande sera généré automatiquement dans PostPersist

        // Copier les produits du panier vers la commande
        foreach ($panier->getPanierProduits() as $panierProduit) {
            $produit = $panierProduit->getProduit();
            
            // Vérifier à nouveau le stock avant de finaliser
            if (!$this->productService->isAvailable($produit, $panierProduit->getQuantite())) {
                throw new BadRequestHttpException(
                    sprintf('Le produit "%s" n\'est plus disponible en quantité suffisante', $produit->getNomProduit())
                );
            }

            // Réduire le stock
            $this->productService->reduceStock($produit, $panierProduit->getQuantite());

            // Créer CommandeProduit
            $commandeProduit = new \App\Entity\CommandeProduit();
            $commandeProduit->setProduit($produit);
            $commandeProduit->setQuantite($panierProduit->getQuantite());
            $commandeProduit->setPrixUnitaire($panierProduit->getPrixUnitaireAsFloat());
            $commandeProduit->setCommande($commande);
            $commande->addCommandeProduit($commandeProduit);
            // Persister explicitement pour éviter les erreurs de cascade
            $this->entityManager->persist($commandeProduit);
        }

        // Générer la facture
        $facture = $commande->genererFacture();
        
        // Persister
        $this->entityManager->persist($commande);
        $this->entityManager->persist($facture);
        $this->entityManager->flush();

        // Générer le numéro de commande après la persistence (pour avoir l'ID)
        if ($commande->getNumeroCommande() === null) {
            $commande->generateNumeroCommande();
            $this->entityManager->flush();
        }

        // Vider le panier
        $this->cartService->clearCart();

        // L'email de confirmation sera envoyé via un EventSubscriber ou un Worker
        // pour ne pas bloquer la réponse HTTP

        return $commande;
    }

    /**
     * Confirmer une commande
     */
    public function confirmOrder(Commande $commande): void
    {
        if ($commande->getStatus() !== Commande::STATUS_PENDING) {
            throw new BadRequestHttpException('Cette commande ne peut pas être confirmée');
        }

        $commande->setStatus(Commande::STATUS_CONFIRMED);
        $this->entityManager->flush();
    }

    /**
     * Annuler une commande
     */
    public function cancelOrder(Commande $commande): void
    {
        if (!$commande->canBeCancelled()) {
            throw new BadRequestHttpException('Cette commande ne peut pas être annulée');
        }

        // Restituer le stock
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $this->productService->increaseStock($produit, $commandeProduit->getQuantite());
        }

        $commande->setStatus(Commande::STATUS_CANCELLED);
        $this->entityManager->flush();
    }

    /**
     * Changer le statut d'une commande
     */
    public function updateOrderStatus(Commande $commande, string $status): void
    {
        $statusValides = [
            Commande::STATUS_PENDING,
            Commande::STATUS_CONFIRMED,
            Commande::STATUS_PROCESSING,
            Commande::STATUS_SHIPPED,
            Commande::STATUS_DELIVERED,
            Commande::STATUS_CANCELLED,
        ];

        if (!in_array($status, $statusValides, true)) {
            throw new BadRequestHttpException('Statut de commande invalide');
        }

        $commande->setStatus($status);
        $this->entityManager->flush();
    }

    /**
     * Générer un numéro de commande unique
     */
    private function generateOrderNumber(): string
    {
        $date = date('Ymd');
        $lastOrder = $this->commandeRepository->findOneBy([], ['id' => 'DESC']);
        
        $sequence = $lastOrder ? (int) substr($lastOrder->getId(), -6) + 1 : 1;
        
        return sprintf('CMD-%s-%06d', $date, $sequence);
    }

    /**
     * Récupérer les commandes d'un utilisateur
     */
    public function getUserOrders(): array
    {
        $user = $this->security->getUser();
        
        if (!$user) {
            return [];
        }

        return $this->commandeRepository->findBy(
            ['user' => $user],
            ['datecommande' => 'DESC']
        );
    }

    /**
     * Récupérer une commande par son numéro
     */
    public function getOrderByNumber(string $numeroCommande): ?Commande
    {
        return $this->commandeRepository->findOneBy(['numeroCommande' => $numeroCommande]);
    }
}

