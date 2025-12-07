<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Vetement;
use App\Entity\Chaussure;
use App\Entity\Accessoire;
use App\Repository\ProduitRepository;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProduitController extends AbstractController
{
    #[Route('/produits', name: 'app_produits')]
    public function index(
        ProduitRepository $produitRepository,
        Request $request
    ): Response {
        $type = $request->query->get('type');
        $produits = [];

        if ($type === 'vetement') {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p INSTANCE OF App\Entity\Vetement')
                ->getQuery()
                ->getResult();
        } elseif ($type === 'chaussure') {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p INSTANCE OF App\Entity\Chaussure')
                ->getQuery()
                ->getResult();
        } elseif ($type === 'accessoire') {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p INSTANCE OF App\Entity\Accessoire')
                ->getQuery()
                ->getResult();
        } else {
            $produits = $produitRepository->findAll();
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'type' => $type,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', requirements: ['id' => '\d+'])]
    public function show(
        Produit $produit,
        AvisRepository $avisRepository
    ): Response {
        // Récupérer les avis du produit
        $avis = $avisRepository->findBy(['produit' => $produit]);

        // Récupérer les promotions actives
        $promotionsActives = [];
        foreach ($produit->getPromotions() as $promotion) {
            if ($promotion->getDateExpiration() >= new \DateTime()) {
                $promotionsActives[] = $promotion;
            }
        }

        // Calculer le prix avec promotion si applicable
        $prixFinal = $produit->getPrix();
        if (!empty($promotionsActives)) {
            $promotion = $promotionsActives[0];
            $prixFinal = $promotion->appliquerPromo($produit->getPrix());
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'avis' => $avis,
            'promotions' => $promotionsActives,
            'prixFinal' => $prixFinal,
        ]);
    }

    #[Route('/produits/vetements', name: 'app_produits_vetements')]
    public function vetements(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->createQueryBuilder('p')
            ->where('p INSTANCE OF App\Entity\Vetement')
            ->getQuery()
            ->getResult();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'type' => 'vetement',
            'titre' => 'Vêtements',
        ]);
    }

    #[Route('/produits/chaussures', name: 'app_produits_chaussures')]
    public function chaussures(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->createQueryBuilder('p')
            ->where('p INSTANCE OF App\Entity\Chaussure')
            ->getQuery()
            ->getResult();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'type' => 'chaussure',
            'titre' => 'Chaussures',
        ]);
    }

    #[Route('/produits/accessoires', name: 'app_produits_accessoires')]
    public function accessoires(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->createQueryBuilder('p')
            ->where('p INSTANCE OF App\Entity\Accessoire')
            ->getQuery()
            ->getResult();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'type' => 'accessoire',
            'titre' => 'Accessoires',
        ]);
    }
}

