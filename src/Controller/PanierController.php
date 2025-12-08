<?php

namespace App\Controller;

use App\Entity\Pannier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Repository\PannierRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/panier')]
#[IsGranted('ROLE_USER')]
class PanierController extends AbstractController
{
    #[Route('/', name: 'app_panier')]
    public function index(PannierRepository $panierRepository): Response
    {
        $user = $this->getUser();
        $panier = $panierRepository->findOneBy(['user' => $user]);

        if (!$panier) {
            $panier = new Pannier();
            $panier->setUser($user);
        }

        $total = $panier->calculerTotal();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'total' => $total,
        ]);
    }

    #[Route('/ajouter/{id}', name: 'app_panier_ajouter', requirements: ['id' => '\d+'])]
    public function ajouter(
        Produit $produit,
        Request $request,
        PannierRepository $panierRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $quantite = (int) $request->request->get('quantite', 1);

        // Récupérer ou créer le panier
        $panier = $panierRepository->findOneBy(['user' => $user]);
        if (!$panier) {
            $panier = new Pannier();
            $panier->setUser($user);
            $entityManager->persist($panier);
        }

        // Ajouter le produit au panier
        $panier->ajouterProduit($produit, $quantite);
        $entityManager->flush();

        $this->addFlash('success', 'Produit ajouté au panier avec succès !');

        // Rediriger vers la page précédente ou le panier
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/modifier/{id}', name: 'app_panier_modifier', requirements: ['id' => '\d+'])]
    public function modifier(
        PanierProduit $panierProduit,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $panier = $panierProduit->getPanier();

        // Vérifier que le panier appartient à l'utilisateur
        if ($panier->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $quantite = (int) $request->request->get('quantite', 1);
        if ($quantite <= 0) {
            $entityManager->remove($panierProduit);
        } else {
            $panierProduit->setQuantite($quantite);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Panier mis à jour !');

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/supprimer/{id}', name: 'app_panier_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(
        PanierProduit $panierProduit,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $panier = $panierProduit->getPanier();

        // Vérifier que le panier appartient à l'utilisateur
        if ($panier->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($panierProduit);
        $entityManager->flush();

        $this->addFlash('success', 'Produit retiré du panier !');

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(
        PannierRepository $panierRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $panier = $panierRepository->findOneBy(['user' => $user]);

        if ($panier) {
            foreach ($panier->getPanierProduits() as $panierProduit) {
                $entityManager->remove($panierProduit);
            }
            $entityManager->flush();
        }

        $this->addFlash('success', 'Panier vidé !');

        return $this->redirectToRoute('app_panier');
    }
}

