<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use App\Entity\Vetement;
use App\Entity\Chaussure;
use App\Entity\Accessoire;
use App\Form\ProduitType;
use App\Form\VetementType;
use App\Form\ChaussureType;
use App\Form\AccessoireType;
use App\Repository\ProduitRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/produits')]
#[IsGranted('ROLE_ADMIN')]
class AdminProduitController extends AbstractController
{
    #[Route('/', name: 'app_admin_produits')]
    public function index(ProduitRepository $produitRepository): Response
    {
        $produits = $produitRepository->findAll();

        return $this->render('admin/produits/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/nouveau', name: 'app_admin_produits_nouveau')]
    public function nouveau(Request $request, EntityManagerInterface $entityManager, ImageUploader $imageUploader): Response
    {
        $type = $request->query->get('type', 'vetement');

        switch ($type) {
            case 'vetement':
                $produit = new Vetement();
                $form = $this->createForm(VetementType::class, $produit);
                break;
            case 'chaussure':
                $produit = new Chaussure();
                $form = $this->createForm(ChaussureType::class, $produit);
                break;
            case 'accessoire':
                $produit = new Accessoire();
                $form = $this->createForm(AccessoireType::class, $produit);
                break;
            default:
                $produit = new Vetement();
                $form = $this->createForm(VetementType::class, $produit);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $imageFilename = $imageUploader->upload($imageFile);
                $produit->setImage($imageFilename);
            }

            $entityManager->persist($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit créé avec succès !');

            return $this->redirectToRoute('app_admin_produits');
        }

        return $this->render('admin/produits/nouveau.html.twig', [
            'form' => $form,
            'type' => $type,
        ]);
    }

    #[Route('/{id}/modifier', name: 'app_admin_produits_modifier', requirements: ['id' => '\d+'])]
    public function modifier(
        Produit $produit,
        Request $request,
        EntityManagerInterface $entityManager,
        ImageUploader $imageUploader
    ): Response {
        $type = $produit->getType();

        switch ($type) {
            case 'vetement':
                $form = $this->createForm(VetementType::class, $produit);
                break;
            case 'chaussure':
                $form = $this->createForm(ChaussureType::class, $produit);
                break;
            case 'accessoire':
                $form = $this->createForm(AccessoireType::class, $produit);
                break;
            default:
                $form = $this->createForm(ProduitType::class, $produit);
        }

        $oldImage = $produit->getImage();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gérer l'upload de l'image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $imageFilename = $imageUploader->upload($imageFile, $oldImage);
                $produit->setImage($imageFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès !');

            return $this->redirectToRoute('app_admin_produits');
        }

        return $this->render('admin/produits/modifier.html.twig', [
            'produit' => $produit,
            'form' => $form,
            'type' => $type,
        ]);
    }

    #[Route('/{id}/supprimer', name: 'app_admin_produits_supprimer', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function supprimer(
        Produit $produit,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager->remove($produit);
            $entityManager->flush();

            $this->addFlash('success', 'Produit supprimé avec succès !');
        }

        return $this->redirectToRoute('app_admin_produits');
    }
}

