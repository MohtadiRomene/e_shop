<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Produit;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/avis')]
#[IsGranted('ROLE_USER')]
class AvisController extends AbstractController
{
    #[Route('/produit/{id}/creer', name: 'app_avis_creer', requirements: ['id' => '\d+'])]
    public function creer(
        Produit $produit,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();
        $contenu = $request->request->get('contenu');

        if (empty($contenu)) {
            $this->addFlash('error', 'Le contenu de l\'avis ne peut pas être vide !');
            return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()]);
        }

        // Créer l'avis
        $avis = $user->ajouterAvis($produit, $contenu);
        $entityManager->persist($avis);
        $entityManager->flush();

        $this->addFlash('success', 'Votre avis a été ajouté avec succès !');

        return $this->redirectToRoute('app_produit_show', ['id' => $produit->getId()]);
    }

    #[Route('/{id}/supprimer', name: 'app_avis_supprimer', requirements: ['id' => '\d+'])]
    public function supprimer(
        Avis $avis,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        // Vérifier que l'avis appartient à l'utilisateur
        if ($avis->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        $produitId = $avis->getProduit()->getId();
        $entityManager->remove($avis);
        $entityManager->flush();

        $this->addFlash('success', 'Avis supprimé avec succès !');

        return $this->redirectToRoute('app_produit_show', ['id' => $produitId]);
    }
}

