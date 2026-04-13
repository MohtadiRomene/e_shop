<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:ensure-default-admin',
    description: 'Crée un administrateur par défaut si nécessaire (idempotent).',
)]
final class EnsureDefaultAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $adminEmail = trim((string) getenv('DEFAULT_ADMIN_EMAIL'));
        $adminPassword = (string) getenv('DEFAULT_ADMIN_PASSWORD');
        $adminNom = trim((string) getenv('DEFAULT_ADMIN_NOM'));
        $adminTel = trim((string) getenv('DEFAULT_ADMIN_TEL'));
        $adminAdresse = trim((string) getenv('DEFAULT_ADMIN_ADRESSE'));

        if ($adminEmail === '' || $adminPassword === '') {
            $io->note(
                'DEFAULT_ADMIN_EMAIL / DEFAULT_ADMIN_PASSWORD manquants. Skip.',
            );
            return Command::SUCCESS;
        }

        $existingUser = $this->userRepository->findOneBy(['email' => $adminEmail]);
        if ($existingUser instanceof User) {
            if (!$existingUser->isAdmin()) {
                $existingUser->setRoles(['ROLE_ADMIN']);
                $this->entityManager->flush();
                $io->success(
                    sprintf('Utilisateur promu admin: %s', $adminEmail),
                );
            } else {
                $io->success(sprintf('Admin déjà présent: %s', $adminEmail));
            }

            return Command::SUCCESS;
        }

        $admin = new User();
        $admin->setEmail($adminEmail);
        $admin->setNom($adminNom !== '' ? $adminNom : 'Administrateur');
        $admin->setTel($adminTel !== '' ? $adminTel : '0123456789');
        $admin->setAdresse(
            $adminAdresse !== '' ? $adminAdresse : 'Adresse admin',
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, $adminPassword),
        );

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success(sprintf('Admin créé: %s', $adminEmail));

        return Command::SUCCESS;
    }
}

