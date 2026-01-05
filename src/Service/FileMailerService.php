<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\RawMessage;

class FileMailerService
{
    private string $mailDirectory;

    public function __construct(
        private MailerInterface $mailer,
        string $projectDir
    ) {
        $this->mailDirectory = $projectDir . '/var/mail';
        
        // Créer le dossier s'il n'existe pas
        if (!is_dir($this->mailDirectory)) {
            mkdir($this->mailDirectory, 0755, true);
        }
    }

    public function send(Email $email): void
    {
        // Sauvegarder l'email dans un fichier
        $filename = date('Y-m-d_H-i-s') . '_' . uniqid() . '.html';
        $filepath = $this->mailDirectory . '/' . $filename;

        $content = $this->formatEmailForFile($email);
        file_put_contents($filepath, $content);

        // Optionnel : aussi envoyer via le mailer normal (si configuré)
        // $this->mailer->send($email);
    }

    private function formatEmailForFile(Email $email): string
    {
        $html = '<!DOCTYPE html>';
        $html .= '<html><head><meta charset="UTF-8">';
        $html .= '<style>body { font-family: Arial, sans-serif; padding: 20px; }</style>';
        $html .= '</head><body>';
        $html .= '<h2>Email sauvegardé (Mode Fichier)</h2>';
        $html .= '<p><strong>De :</strong> ' . htmlspecialchars(implode(', ', array_keys($email->getFrom()))) . '</p>';
        $html .= '<p><strong>À :</strong> ' . htmlspecialchars(implode(', ', array_keys($email->getTo()))) . '</p>';
        $html .= '<p><strong>Sujet :</strong> ' . htmlspecialchars($email->getSubject()) . '</p>';
        $html .= '<hr>';
        $html .= '<div>' . $email->getHtmlBody() . '</div>';
        $html .= '</body></html>';
        
        return $html;
    }
}
