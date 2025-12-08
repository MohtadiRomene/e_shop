<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Facture;
use App\Repository\CommandeRepository;
use App\Repository\PannierRepository;
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
        PannierRepository $panierRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $panier = $panierRepository->findOneBy(['user' => $user]);

        if (!$panier || $panier->getPanierProduits()->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide !');
            return $this->redirectToRoute('app_panier');
        }

        // Créer la commande à partir du panier
        $commande = $panier->passerCommande();
        $entityManager->persist($commande);

        // Générer la facture
        $facture = $commande->genererFacture();
        $entityManager->persist($facture);

        // Vider le panier
        foreach ($panier->getPanierProduits() as $panierProduit) {
            $entityManager->remove($panierProduit);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Commande passée avec succès !');

        return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
    }

    #[Route('/{id}/facture', name: 'app_commande_facture', requirements: ['id' => '\d+'])]
    public function facture(Commande $commande): Response
    {
        $user = $this->getUser();

        // Vérifier que la commande appartient à l'utilisateur
        if ($commande->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $facture = $commande->getFacture();
        if (!$facture) {
            $this->addFlash('error', 'Facture introuvable !');
            return $this->redirectToRoute('app_commande_show', ['id' => $commande->getId()]);
        }

        return $this->render('commande/facture.html.twig', [
            'commande' => $commande,
            'facture' => $facture,
        ]);
    }
}

