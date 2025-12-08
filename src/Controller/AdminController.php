<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Vetement;
use App\Entity\Chaussure;
use App\Entity\Accessoire;
use App\Entity\Promotion;
use App\Entity\Commande;
use App\Repository\ProduitRepository;
use App\Repository\PromotionRepository;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(
        CommandeRepository $commandeRepository,
        ProduitRepository $produitRepository,
        UserRepository $userRepository,
        PromotionRepository $promotionRepository
    ): Response {
        // Commandes récentes
        $commandesRecentes = $commandeRepository->findBy([], ['datecommande' => 'DESC'], 5);
        
        // Statistiques générales
        $totalProduits = count($produitRepository->findAll());
        $totalCommandes = count($commandeRepository->findAll());
        $totalClients = count($userRepository->findBy(['roles' => ['ROLE_CLIENT']]));
        $totalPromotions = count($promotionRepository->findAll());
        
        // Chiffre d'affaires total
        $chiffreAffairesTotal = $commandeRepository->createQueryBuilder('c')
            ->select('SUM(c.prixtotal)')
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
        
        // Chiffre d'affaires du mois en cours
        $dateDebutMois = new \DateTime('first day of this month');
        $dateFinMois = new \DateTime('last day of this month');
        $chiffreAffairesMois = $commandeRepository->createQueryBuilder('c')
            ->select('SUM(c.prixtotal)')
            ->where('c.datecommande >= :dateDebut')
            ->andWhere('c.datecommande <= :dateFin')
            ->setParameter('dateDebut', $dateDebutMois)
            ->setParameter('dateFin', $dateFinMois)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;
        
        // Commandes du mois
        $commandesMois = $commandeRepository->createQueryBuilder('c')
            ->where('c.datecommande >= :dateDebut')
            ->andWhere('c.datecommande <= :dateFin')
            ->setParameter('dateDebut', $dateDebutMois)
            ->setParameter('dateFin', $dateFinMois)
            ->getQuery()
            ->getResult();
        $totalCommandesMois = count($commandesMois);
        
        // Produits par type
        $vetements = count($produitRepository->createQueryBuilder('p')
            ->where('p INSTANCE OF App\Entity\Vetement')
            ->getQuery()
            ->getResult());
        $chaussures = count($produitRepository->createQueryBuilder('p')
            ->where('p INSTANCE OF App\Entity\Chaussure')
            ->getQuery()
            ->getResult());
        $accessoires = count($produitRepository->createQueryBuilder('p')
            ->where('p INSTANCE OF App\Entity\Accessoire')
            ->getQuery()
            ->getResult());

        return $this->render('admin/index.html.twig', [
            'commandesRecentes' => $commandesRecentes,
            'totalProduits' => $totalProduits,
            'totalCommandes' => $totalCommandes,
            'totalClients' => $totalClients,
            'totalPromotions' => $totalPromotions,
            'chiffreAffairesTotal' => $chiffreAffairesTotal,
            'chiffreAffairesMois' => $chiffreAffairesMois,
            'totalCommandesMois' => $totalCommandesMois,
            'vetements' => $vetements,
            'chaussures' => $chaussures,
            'accessoires' => $accessoires,
        ]);
    }
}

