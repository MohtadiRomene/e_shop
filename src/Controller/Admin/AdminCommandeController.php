<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/commandes')]
#[IsGranted('ROLE_ADMIN')]
class AdminCommandeController extends AbstractController
{
    #[Route('/', name: 'app_admin_commandes')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        $commandes = $commandeRepository->findBy([], ['datecommande' => 'DESC']);

        return $this->render('admin/commandes/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_commandes_show', requirements: ['id' => '\d+'])]
    public function show(Commande $commande): Response
    {
        return $this->render('admin/commandes/show.html.twig', [
            'commande' => $commande,
        ]);
    }
}

