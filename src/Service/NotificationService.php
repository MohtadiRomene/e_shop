<?php

namespace App\Service;

use App\Entity\Commande;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Service pour gérer les notifications (emails)
 */
class NotificationService
{
    public function __construct(
        private MailerInterface $mailer,
        private string $fromEmail = 'noreply@eshop.com',
        private string $fromName = 'E-Shop'
    ) {
    }

    /**
     * Envoyer un email de confirmation de commande
     */
    public function sendOrderConfirmation(Commande $commande): void
    {
        $user = $commande->getUser();
        
        if (!$user || !$user->getEmail()) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to(new Address($user->getEmail(), $user->getNom()))
            ->subject('Confirmation de votre commande #' . $commande->getNumeroCommande())
            ->htmlTemplate('emails/order_confirmation.html.twig')
            ->context([
                'commande' => $commande,
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }

    /**
     * Envoyer un email de mise à jour du statut de commande
     */
    public function sendOrderStatusUpdate(Commande $commande): void
    {
        $user = $commande->getUser();
        
        if (!$user || !$user->getEmail()) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to(new Address($user->getEmail(), $user->getNom()))
            ->subject('Mise à jour de votre commande #' . $commande->getNumeroCommande())
            ->htmlTemplate('emails/order_status_update.html.twig')
            ->context([
                'commande' => $commande,
                'user' => $user,
                'status' => $commande->getStatus(),
            ]);

        $this->mailer->send($email);
    }

    /**
     * Envoyer un email de bienvenue
     */
    public function sendWelcomeEmail(User $user): void
    {
        if (!$user->getEmail()) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to(new Address($user->getEmail(), $user->getNom()))
            ->subject('Bienvenue sur E-Shop !')
            ->htmlTemplate('emails/welcome.html.twig')
            ->context([
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }

    /**
     * Envoyer un email de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail(User $user, string $resetToken): void
    {
        if (!$user->getEmail()) {
            return;
        }

        $email = (new TemplatedEmail())
            ->from(new Address($this->fromEmail, $this->fromName))
            ->to(new Address($user->getEmail(), $user->getNom()))
            ->subject('Réinitialisation de votre mot de passe')
            ->htmlTemplate('emails/password_reset.html.twig')
            ->context([
                'user' => $user,
                'resetToken' => $resetToken,
            ]);

        $this->mailer->send($email);
    }
}

