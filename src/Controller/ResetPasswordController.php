<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    #[Route('/forgot-password', name: 'app_forgot_password', methods: ['GET', 'POST'])]
    public function request(Request $request): Response
    {
        $emailSent = false;
        $error = null;

        if ($request->isMethod('POST')) {
            $email = trim($request->request->get('email', ''));

            if (empty($email)) {
                $error = 'Veuillez entrer votre adresse email.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Veuillez entrer une adresse email valide.';
            } else {
                $user = $this->userRepository->findOneBy(['email' => $email]);

                // Pour des raisons de sécurité, on ne révèle pas si l'email existe ou non
                if ($user) {
                    // Générer un token unique
                    $token = bin2hex(random_bytes(32));
                    $user->setResetToken($token);
                    $user->setResetTokenExpiresAt(new \DateTime('+1 hour')); // Token valide pendant 1 heure
                    $this->entityManager->flush();

                    // Envoyer l'email de réinitialisation
                    try {
                        $resetUrl = $this->generateUrl(
                            'app_reset_password',
                            ['token' => $token],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );

                        $emailMessage = (new Email())
                            ->from('noreply@kaira.com')
                            ->to($user->getEmail())
                            ->subject('Réinitialisation de votre mot de passe - Kaira Fashion Store')
                            ->html($this->renderView('reset_password/email.html.twig', [
                                'user' => $user,
                                'resetUrl' => $resetUrl,
                                'token' => $token,
                            ]));

                        // Envoyer l'email via le mailer Symfony
                        $this->mailer->send($emailMessage);
                        $emailSent = true;
                    } catch (\Exception $e) {
                        $error = 'Une erreur est survenue lors de l\'envoi de l\'email : ' . $e->getMessage();
                    }
                } else {
                    // Pour des raisons de sécurité, on affiche le même message
                    $emailSent = true;
                }
            }

            if ($error) {
                $this->addFlash('error', $error);
            } else {
                // Message de succès (même si l'email n'existe pas, pour la sécurité)
                $this->addFlash('success', 'Si un compte existe avec cette adresse email, un lien de réinitialisation a été envoyé à votre boîte mail. Vérifiez votre boîte de réception et vos spams.');
            }

            // Rediriger après POST pour éviter les problèmes avec Turbo
            return $this->redirectToRoute('app_forgot_password');
        }

        return $this->render('reset_password/request.html.twig', [
            'emailSent' => $emailSent,
        ]);
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function reset(string $token, Request $request): Response
    {
        $user = $this->userRepository->findOneBy(['resetToken' => $token]);

        if (!$user || !$user->isResetTokenValid()) {
            $this->addFlash('error', 'Le lien de réinitialisation est invalide ou a expiré. Veuillez faire une nouvelle demande.');
            return $this->redirectToRoute('app_forgot_password');
        }

        $success = false;
        $error = null;

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if (empty($password)) {
                $error = 'Veuillez entrer un nouveau mot de passe.';
            } elseif (strlen($password) < 6) {
                $error = 'Le mot de passe doit contenir au moins 6 caractères.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Les mots de passe ne correspondent pas.';
            } else {
                // Réinitialiser le mot de passe
                $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
                $user->setResetToken(null);
                $user->setResetTokenExpiresAt(null);
                $this->entityManager->flush();

                $success = true;
                $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.');
            }

            if ($error) {
                $this->addFlash('error', $error);
            }
        }

        return $this->render('reset_password/reset.html.twig', [
            'token' => $token,
            'success' => $success,
        ]);
    }
}
