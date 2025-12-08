<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, ProduitRepository $produitRepository): Response
    {
        $query = $request->query->get('q', '');
        $type = $request->query->get('type');
        $minPrice = $request->query->get('min_price') ? (float) $request->query->get('min_price') : null;
        $maxPrice = $request->query->get('max_price') ? (float) $request->query->get('max_price') : null;

        $produits = [];

        if (!empty($query)) {
            if ($minPrice !== null || $maxPrice !== null) {
                $produits = $produitRepository->searchAdvanced($query, $minPrice, $maxPrice, $type);
            } else {
                $produits = $produitRepository->search($query, $type);
            }
        }

        return $this->render('search/results.html.twig', [
            'produits' => $produits,
            'query' => $query,
            'type' => $type,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);
    }
}

