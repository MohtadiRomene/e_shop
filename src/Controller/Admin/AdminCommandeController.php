<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/{id}/changer-statut', name: 'app_admin_commandes_changer_statut', methods: ['POST'], requirements: ['id' => '\d+'])]
    public function changerStatut(
        Commande $commande,
        Request $request,
        \App\Service\OrderService $orderService
    ): Response {
        $nouveauStatut = $request->request->get('statut');
        
        if ($nouveauStatut) {
            try {
                $orderService->updateOrderStatus($commande, $nouveauStatut);
                $this->addFlash('success', 'Statut de la commande mis à jour avec succès');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->redirectToRoute('app_admin_commandes_show', ['id' => $commande->getId()]);
    }
}

