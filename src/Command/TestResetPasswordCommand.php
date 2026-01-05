<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[AsCommand(
    name: 'app:test-reset-password',
    description: 'Test la fonctionnalité de réinitialisation de mot de passe',
)]
class TestResetPasswordCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('email', null, InputOption::VALUE_REQUIRED, 'Email de l\'utilisateur à tester')
            ->addOption('create-token', null, InputOption::VALUE_NONE, 'Créer un token de test sans envoyer d\'email');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $input->getOption('email');
        $createTokenOnly = $input->getOption('create-token');

        if (!$email) {
            // Lister tous les utilisateurs
            $users = $this->userRepository->findAll();
            if (empty($users)) {
                $io->error('Aucun utilisateur trouvé dans la base de données.');
                return Command::FAILURE;
            }

            $io->title('Utilisateurs disponibles :');
            $userChoices = [];
            foreach ($users as $user) {
                $userChoices[] = $user->getEmail();
                $io->writeln(sprintf('  - %s (%s)', $user->getEmail(), $user->getNom()));
            }

            $email = $io->choice('Sélectionnez un utilisateur', $userChoices);
        }

        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $io->error(sprintf('Aucun utilisateur trouvé avec l\'email : %s', $email));
            return Command::FAILURE;
        }

        $io->section('Test de réinitialisation de mot de passe');
        $io->writeln(sprintf('Utilisateur : %s (%s)', $user->getEmail(), $user->getNom()));

        // Générer un token
        $token = bin2hex(random_bytes(32));
        $user->setResetToken($token);
        $user->setResetTokenExpiresAt(new \DateTime('+1 hour'));
        $this->entityManager->flush();

        $io->success('Token généré avec succès !');
        $io->writeln(sprintf('Token : <info>%s</info>', $token));
        $io->writeln(sprintf('Expire le : <info>%s</info>', $user->getResetTokenExpiresAt()->format('Y-m-d H:i:s')));

        // Générer l'URL de réinitialisation
        $resetUrl = $this->urlGenerator->generate(
            'app_reset_password',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $io->writeln('');
        $io->section('URL de réinitialisation');
        $io->writeln($resetUrl);

        if (!$createTokenOnly) {
            // Tester l'envoi d'email
            $io->writeln('');
            $io->section('Test d\'envoi d\'email');

            try {
                $emailMessage = (new Email())
                    ->from('noreply@kaira.com')
                    ->to($user->getEmail())
                    ->subject('Test - Réinitialisation de votre mot de passe - Kaira Fashion Store')
                    ->html(sprintf(
                        '<h2>Test de réinitialisation de mot de passe</h2>
                        <p>Bonjour %s,</p>
                        <p>Ceci est un email de test pour la réinitialisation de mot de passe.</p>
                        <p><strong>Token :</strong> %s</p>
                        <p><a href="%s">Cliquez ici pour réinitialiser votre mot de passe</a></p>
                        <p>Ce lien est valide pendant 1 heure.</p>',
                        htmlspecialchars($user->getNom()),
                        htmlspecialchars($token),
                        htmlspecialchars($resetUrl)
                    ));

                // Envoyer l'email via le mailer Symfony
                $this->mailer->send($emailMessage);
                $io->success('Email envoyé avec succès à votre boîte mail !');
            } catch (\Exception $e) {
                $io->error(sprintf('Erreur lors de la sauvegarde de l\'email : %s', $e->getMessage()));
                $io->note('Vérifiez que le dossier var/mail/ existe et est accessible en écriture');
                return Command::FAILURE;
            }
        }

        $io->writeln('');
        $io->section('Instructions');
        $io->writeln('1. Copiez l\'URL ci-dessus');
        $io->writeln('2. Ouvrez-la dans votre navigateur');
        $io->writeln('3. Entrez un nouveau mot de passe');
        $io->writeln('4. Connectez-vous avec le nouveau mot de passe');

        return Command::SUCCESS;
    }
}
