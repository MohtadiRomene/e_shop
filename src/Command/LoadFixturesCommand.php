<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Vetement;
use App\Entity\Chaussure;
use App\Entity\Accessoire;
use App\Entity\Promotion;
use App\Entity\Pannier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:load-fixtures',
    description: 'Charge des données de test dans la base de données',
)]
class LoadFixturesCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Chargement des fixtures...');

        // Créer un administrateur
        $admin = new User();
        $admin->setEmail('admin@kaira.com');
        $admin->setNom('Administrateur');
        $admin->setTel('0123456789');
        $admin->setAdresse('123 Rue Admin, Paris');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($admin);
        $io->success('Administrateur créé: admin@kaira.com / admin123');

        // Créer des utilisateurs clients
        $clients = [
            ['email' => 'client1@example.com', 'nom' => 'Jean Dupont', 'tel' => '0612345678', 'adresse' => '10 Rue de la Paix, Paris'],
            ['email' => 'client2@example.com', 'nom' => 'Marie Martin', 'tel' => '0623456789', 'adresse' => '20 Avenue des Champs, Lyon'],
            ['email' => 'client3@example.com', 'nom' => 'Pierre Bernard', 'tel' => '0634567890', 'adresse' => '30 Boulevard Saint-Michel, Marseille'],
        ];

        foreach ($clients as $clientData) {
            $client = new User();
            $client->setEmail($clientData['email']);
            $client->setNom($clientData['nom']);
            $client->setTel($clientData['tel']);
            $client->setAdresse($clientData['adresse']);
            $client->setPassword($this->passwordHasher->hashPassword($client, 'client123'));
            $client->setRoles(['ROLE_CLIENT']);
            $this->entityManager->persist($client);

            // Créer un panier pour chaque client
            $panier = new Pannier();
            $panier->setUser($client);
            $this->entityManager->persist($panier);
        }
        $io->success(count($clients) . ' clients créés (mot de passe: client123)');

        // Créer des vêtements
        $vetements = [
            ['nom' => 'Veste en Cuir Classique', 'prix' => 299.00, 'taille' => 'M', 'couleur' => 'Noir', 'genre' => 'Homme'],
            ['nom' => 'Robe Élégante Soirée', 'prix' => 189.00, 'taille' => 'S', 'couleur' => 'Rouge', 'genre' => 'Femme'],
            ['nom' => 'Pull en Laine Douce', 'prix' => 79.00, 'taille' => 'L', 'couleur' => 'Beige', 'genre' => 'Unisexe'],
            ['nom' => 'Chemise Blanche Formelle', 'prix' => 89.00, 'taille' => 'M', 'couleur' => 'Blanc', 'genre' => 'Homme'],
            ['nom' => 'Jupe Midi Plissée', 'prix' => 69.00, 'taille' => 'S', 'couleur' => 'Bleu', 'genre' => 'Femme'],
            ['nom' => 'Pantalon Taille Haute', 'prix' => 99.00, 'taille' => 'M', 'couleur' => 'Noir', 'genre' => 'Femme'],
            ['nom' => 'Blazer Élégant', 'prix' => 249.00, 'taille' => 'L', 'couleur' => 'Bleu Marine', 'genre' => 'Homme'],
        ];

        foreach ($vetements as $vetementData) {
            $vetement = new Vetement();
            $vetement->setNomProduit($vetementData['nom']);
            $vetement->setPrix($vetementData['prix']);
            $vetement->setTaille($vetementData['taille']);
            $vetement->setCouleur($vetementData['couleur']);
            $vetement->setGenre($vetementData['genre']);
            $this->entityManager->persist($vetement);
        }
        $io->success(count($vetements) . ' vêtements créés');

        // Créer des chaussures
        $chaussures = [
            ['nom' => 'Baskets Sport Premium', 'prix' => 129.00, 'pointure' => 42],
            ['nom' => 'Escarpins Élégants', 'prix' => 149.00, 'pointure' => 38],
            ['nom' => 'Bottes en Cuir', 'prix' => 199.00, 'pointure' => 40],
            ['nom' => 'Mocassins Classiques', 'prix' => 119.00, 'pointure' => 41],
            ['nom' => 'Sandales Été', 'prix' => 59.00, 'pointure' => 39],
            ['nom' => 'Bottines Automne', 'prix' => 179.00, 'pointure' => 40],
        ];

        foreach ($chaussures as $chaussureData) {
            $chaussure = new Chaussure();
            $chaussure->setNomProduit($chaussureData['nom']);
            $chaussure->setPrix($chaussureData['prix']);
            $chaussure->setPointure($chaussureData['pointure']);
            $this->entityManager->persist($chaussure);
        }
        $io->success(count($chaussures) . ' chaussures créées');

        // Créer des accessoires
        $accessoires = [
            ['nom' => 'Sac à Main Cuir', 'prix' => 179.00, 'type' => 'Sac'],
            ['nom' => 'Ceinture en Cuir', 'prix' => 49.00, 'type' => 'Ceinture'],
            ['nom' => 'Montre Élégante', 'prix' => 299.00, 'type' => 'Montre'],
            ['nom' => 'Lunettes de Soleil', 'prix' => 89.00, 'type' => 'Lunettes'],
            ['nom' => 'Collier Perles', 'prix' => 129.00, 'type' => 'Bijoux'],
            ['nom' => 'Portefeuille Cuir', 'prix' => 79.00, 'type' => 'Portefeuille'],
        ];

        foreach ($accessoires as $accessoireData) {
            $accessoire = new Accessoire();
            $accessoire->setNomProduit($accessoireData['nom']);
            $accessoire->setPrix($accessoireData['prix']);
            $accessoire->setAccessoireType($accessoireData['type']);
            $this->entityManager->persist($accessoire);
        }
        $io->success(count($accessoires) . ' accessoires créés');

        // Créer des promotions
        $promotions = [
            ['reduction' => 20.0, 'dateExpiration' => new \DateTime('+30 days')],
            ['reduction' => 15.0, 'dateExpiration' => new \DateTime('+15 days')],
            ['reduction' => 30.0, 'dateExpiration' => new \DateTime('+7 days')],
        ];

        foreach ($promotions as $promoData) {
            $promotion = new Promotion();
            $promotion->setReduction($promoData['reduction']);
            $promotion->setDateExpiration($promoData['dateExpiration']);
            $this->entityManager->persist($promotion);
        }
        $io->success(count($promotions) . ' promotions créées');

        $this->entityManager->flush();

        // Associer des produits aux promotions après la persistance
        $allProduits = $this->entityManager->getRepository(\App\Entity\Produit::class)->findAll();
        $allPromotions = $this->entityManager->getRepository(Promotion::class)->findAll();

        if (!empty($allProduits) && !empty($allPromotions)) {
            // Associer les 3 premiers produits à la première promotion
            for ($i = 0; $i < min(3, count($allProduits)); $i++) {
                $allPromotions[0]->addProduit($allProduits[$i]);
            }
            // Associer les produits suivants à la deuxième promotion
            for ($i = 3; $i < min(6, count($allProduits)); $i++) {
                if (isset($allPromotions[1])) {
                    $allPromotions[1]->addProduit($allProduits[$i]);
                }
            }
        }

        $this->entityManager->flush();

        $io->success('Fixtures chargées avec succès !');
        $io->note('Comptes créés:');
        $io->listing([
            'Admin: admin@kaira.com / admin123',
            'Client 1: client1@example.com / client123',
            'Client 2: client2@example.com / client123',
            'Client 3: client3@example.com / client123',
        ]);

        return Command::SUCCESS;
    }
}

