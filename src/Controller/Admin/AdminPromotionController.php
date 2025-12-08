<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/promotions')]
#[IsGranted('ROLE_ADMIN')]
class AdminPromotionController extends AbstractController
{
    #[Route('/', name: 'app_admin_promotions')]
    public function index(PromotionRepository $promotionRepository): Response
    {
        $promotions = $promotionRepository->findAll();

        return $this->render('admin/promotions/index.html.twig', [
            'promotions' => $promotions,
        ]);
    }

    #[Route('/nouvelle', name: 'app_admin_promotions_nouvelle')]
    public function nouvelle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($promotion);
            $entityManager->flush();

            $this->addFlash('success', 'Promotion créée avec succès !');

            return $this->redirectToRoute('app_admin_promotions');
        }

        return $this->render('admin/promotions/nouvelle.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_admin_promotions_modifier', requirements: ['id' => '\d+'])]
    public function modifier(
        Promotion $promotion,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Promotion modifiée avec succès !');

            return $this->redirectToRoute('app_admin_promotions');
        }

        return $this->render('admin/promotions/modifier.html.twig', [
            'promotion' => $promotion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'app_admin_promotions_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimer(
        Promotion $promotion,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $promotion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($promotion);
            $entityManager->flush();

            $this->addFlash('success', 'Promotion supprimée avec succès !');
        }

        return $this->redirectToRoute('app_admin_promotions');
    }
}

