<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\AdminCreateUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/admin/users')]
#[IsGranted('ROLE_ADMIN')]
final class AdminUserController extends AbstractController
{
    #[Route('/nouveau-admin', name: 'app_admin_users_nouveau_admin')]
    public function nouveauAdmin(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $user = new User();
        $form = $this->createForm(AdminCreateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingUser = $userRepository->findOneBy([
                'email' => $user->getEmail(),
            ]);
            if ($existingUser instanceof User) {
                $this->addFlash(
                    'error',
                    'Un utilisateur existe déjà avec cet email.',
                );
            } else {
                $plainPassword = (string) $form->get('plainPassword')->getData();
                $user->setRoles(['ROLE_ADMIN']);
                $user->setPassword(
                    $passwordHasher->hashPassword($user, $plainPassword),
                );

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Administrateur créé avec succès !');

                return $this->redirectToRoute('app_admin');
            }
        }

        return $this->render('admin/users/nouveau_admin.html.twig', [
            'form' => $form,
        ]);
    }
}

