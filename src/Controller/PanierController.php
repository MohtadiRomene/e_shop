<?php

namespace App\Controller;

use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Repository\PannierRepository;
use App\Repository\ProduitRepository;
use App\Service\CartService;
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
    public function index(CartService $cartService): Response
    {
        $panier = $cartService->getOrCreateCart();
        $total = $cartService->calculateTotal($panier);
        $errors = $cartService->validateCart($panier);

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'total' => $total,
            'errors' => $errors,
        ]);
    }

    #[Route('/ajouter/{id}', name: 'app_panier_ajouter', requirements: ['id' => '\d+'])]
    public function ajouter(
        Produit $produit,
        Request $request,
        CartService $cartService
    ): Response {
        $quantite = (int) $request->request->get('quantite', 1);

        try {
            $cartService->addProductToCart($produit, $quantite);
            $this->addFlash('success', 'Produit ajouté au panier avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

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
        CartService $cartService
    ): Response {
        $quantite = (int) $request->request->get('quantite', 1);

        try {
            $cartService->updateProductQuantity($panierProduit, $quantite);
            $this->addFlash('success', 'Panier mis à jour !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/supprimer/{id}', name: 'app_panier_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(
        PanierProduit $panierProduit,
        CartService $cartService
    ): Response {
        try {
            $cartService->removeProductFromCart($panierProduit);
            $this->addFlash('success', 'Produit retiré du panier !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/vider', name: 'app_panier_vider')]
    public function vider(CartService $cartService): Response
    {
        $cartService->clearCart();
        $this->addFlash('success', 'Panier vidé !');

        return $this->redirectToRoute('app_panier');
    }
}

