<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Vetement;
use App\Entity\Chaussure;
use App\Entity\Accessoire;
use App\Repository\ProduitRepository;
use App\Repository\AvisRepository;
use App\Service\ProductService;
use App\Service\PaginationHelper;
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
        PaginationHelper $paginationHelper,
        Request $request
    ): Response {
        $type = $request->query->get('type');
        $produits = [];

        if ($type === 'vetement') {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p INSTANCE OF App\Entity\Vetement')
                ->orderBy('p.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        } elseif ($type === 'chaussure') {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p INSTANCE OF App\Entity\Chaussure')
                ->orderBy('p.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        } elseif ($type === 'accessoire') {
            $produits = $produitRepository->createQueryBuilder('p')
                ->where('p INSTANCE OF App\Entity\Accessoire')
                ->orderBy('p.createdAt', 'DESC')
                ->getQuery()
                ->getResult();
        } else {
            $produits = $produitRepository->findBy([], ['createdAt' => 'DESC']);
        }

        $pagination = $paginationHelper->paginate($produits, $request, 12);

        return $this->render('produit/index.html.twig', [
            'produits' => $pagination['items'],
            'pagination' => $pagination,
            'type' => $type,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit_show', requirements: ['id' => '\d+'])]
    public function show(
        Produit $produit,
        AvisRepository $avisRepository,
        ProductService $productService
    ): Response {
        // Récupérer les avis du produit (tri par date)
        $avis = $avisRepository->findBy(
            ['produit' => $produit],
            ['date' => 'DESC']
        );

        // Récupérer les promotions actives via le service
        $promotionsActives = $productService->getActivePromotions($produit)->toArray();

        // Calculer le prix final avec promotion via le service
        $prixFinal = $productService->calculateFinalPrice($produit);

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'avis' => $avis,
            'promotions' => $promotionsActives,
            'prixFinal' => $prixFinal,
            'isAvailable' => $productService->isAvailable($produit),
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

