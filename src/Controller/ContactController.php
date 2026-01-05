<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $success = false;
        $error = null;

        if ($request->isMethod('POST')) {
            $nom = $request->request->get('nom');
            $email = $request->request->get('email');
            $sujet = $request->request->get('sujet');
            $message = $request->request->get('message');

            // Validation basique
            if (empty($nom) || empty($email) || empty($message)) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Veuillez entrer une adresse email valide.';
            } else {
                try {
                    // Envoyer l'email (dans un environnement de production, configurer MAILER_DSN)
                    $emailMessage = (new Email())
                        ->from($email)
                        ->to('contact@kaira.com')
                        ->subject('Contact depuis le site : ' . ($sujet ?: 'Sans sujet'))
                        ->html(sprintf(
                            '<h2>Nouveau message de contact</h2>
                            <p><strong>Nom :</strong> %s</p>
                            <p><strong>Email :</strong> %s</p>
                            <p><strong>Sujet :</strong> %s</p>
                            <p><strong>Message :</strong></p>
                            <p>%s</p>',
                            htmlspecialchars($nom),
                            htmlspecialchars($email),
                            htmlspecialchars($sujet ?: 'Sans sujet'),
                            nl2br(htmlspecialchars($message))
                        ));

                    $mailer->send($emailMessage);
                    $success = true;
                    $this->addFlash('success', 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
                } catch (\Exception $e) {
                    $error = 'Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer plus tard.';
                }
            }

            if ($error) {
                $this->addFlash('error', $error);
            }
        }

        return $this->render('contact/index.html.twig', [
            'success' => $success,
        ]);
    }
}
