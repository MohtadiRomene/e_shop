<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Facture;
use App\Repository\CommandeRepository;
use App\Service\CartService;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commandes')]
#[IsGranted('ROLE_USER')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commandes')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $user = $this->getUser();
        $commandes = $commandeRepository->findBy(
            ['user' => $user],
            ['datecommande' => 'DESC']
        );

        return $this->render('commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', requirements: ['id' => '\d+'])]
    public function show(Commande $commande): Response
    {
        $user = $this->getUser();

        // Vérifier que la commande appartient à l'utilisateur
        if ($commande->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/creer', name: 'app_commande_creer')]
    public function creer(
        CartService $cartService,
        OrderService $orderService
    ): Response {
        try {
            $panier = $cartService->getOrCreateCart();
            $commande = $orderService->createOrderFromCart($panier);
            
            $this->addFlash('success', 'Commande passée avec succès ! Votre numéro de commande: ' . $commande->getNumeroCommande());
            
            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('app_panier');
        }
    }

    #[Route('/{id}/facture', name: 'app_commande_facture', requirements: ['id' => '\d+'])]
    public function facture(Commande $commande): Response
    {
        $user = $this->getUser();

        // Les admins peuvent voir toutes les factures, les utilisateurs uniquement les leurs
        if (!$this->isGranted('ROLE_ADMIN') && $commande->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $facture = $commande->getFacture();
        if (!$facture) {
            $this->addFlash('error', 'Facture introuvable !');
            $redirectRoute = $this->isGranted('ROLE_ADMIN') 
                ? 'app_admin_commandes_show' 
                : 'app_commande_show';
            return $this->redirectToRoute($redirectRoute, ['id' => $commande->getId()]);
        }

        return $this->render('commande/facture.html.twig', [
            'commande' => $commande,
            'facture' => $facture,
        ]);
    }
}

