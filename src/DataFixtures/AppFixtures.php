<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vetement;
use App\Entity\Chaussure;
use App\Entity\Accessoire;
use App\Entity\Promotion;
use App\Entity\Pannier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un administrateur
        $admin = new User();
        $admin->setEmail('admin@kaira.com');
        $admin->setNom('Administrateur');
        $admin->setTel('0123456789');
        $admin->setAdresse('123 Rue Admin, Paris');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

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
            $manager->persist($client);

            // Créer un panier pour chaque client
            $panier = new Pannier();
            $panier->setUser($client);
            $manager->persist($panier);
        }

        // Créer des vêtements
        $vetements = [
            ['nom' => 'Veste en Cuir Classique', 'prix' => 299.00, 'taille' => 'M', 'couleur' => 'Noir', 'genre' => 'Homme', 'image' => 'product-item-1.jpg'],
            ['nom' => 'Robe Élégante Soirée', 'prix' => 189.00, 'taille' => 'S', 'couleur' => 'Rouge', 'genre' => 'Femme', 'image' => 'product-item-2.jpg'],
            ['nom' => 'Pull en Laine Douce', 'prix' => 79.00, 'taille' => 'L', 'couleur' => 'Beige', 'genre' => 'Unisexe', 'image' => 'product-item-3.jpg'],
            ['nom' => 'Chemise Blanche Formelle', 'prix' => 89.00, 'taille' => 'M', 'couleur' => 'Blanc', 'genre' => 'Homme', 'image' => 'product-item-4.jpg'],
            ['nom' => 'Jupe Midi Plissée', 'prix' => 69.00, 'taille' => 'S', 'couleur' => 'Bleu', 'genre' => 'Femme', 'image' => 'product-item-5.jpg'],
        ];

        foreach ($vetements as $vetementData) {
            $vetement = new Vetement();
            $vetement->setNomProduit($vetementData['nom']);
            $vetement->setPrix($vetementData['prix']);
            $vetement->setTaille($vetementData['taille']);
            $vetement->setCouleur($vetementData['couleur']);
            $vetement->setGenre($vetementData['genre']);
            $vetement->setImage($vetementData['image']);
            // Définir le stock (entre 10 et 50 unités aléatoirement)
            $stock = rand(10, 50);
            $vetement->setStock($stock);
            $vetement->setEnStock(true);
            $manager->persist($vetement);
        }

        // Créer des chaussures
        $chaussures = [
            ['nom' => 'Baskets Sport Premium', 'prix' => 129.00, 'pointure' => 42, 'image' => 'product-item-6.jpg'],
            ['nom' => 'Escarpins Élégants', 'prix' => 149.00, 'pointure' => 38, 'image' => 'product-item-7.jpg'],
            ['nom' => 'Bottes en Cuir', 'prix' => 199.00, 'pointure' => 40, 'image' => 'product-item-8.jpg'],
            ['nom' => 'Mocassins Classiques', 'prix' => 119.00, 'pointure' => 41, 'image' => 'product-item-9.jpg'],
            ['nom' => 'Sandales Été', 'prix' => 59.00, 'pointure' => 39, 'image' => 'product-item-10.jpg'],
        ];

        foreach ($chaussures as $chaussureData) {
            $chaussure = new Chaussure();
            $chaussure->setNomProduit($chaussureData['nom']);
            $chaussure->setPrix($chaussureData['prix']);
            $chaussure->setPointure($chaussureData['pointure']);
            $chaussure->setImage($chaussureData['image']);
            // Définir le stock (entre 10 et 50 unités aléatoirement)
            $stock = rand(10, 50);
            $chaussure->setStock($stock);
            $chaussure->setEnStock(true);
            $manager->persist($chaussure);
        }

        // Créer des accessoires
        $accessoires = [
            ['nom' => 'Sac à Main Cuir', 'prix' => 179.00, 'type' => 'Sac', 'image' => 'wishlist-item1.jpg'],
            ['nom' => 'Ceinture en Cuir', 'prix' => 49.00, 'type' => 'Ceinture', 'image' => 'wishlist-item2.jpg'],
            ['nom' => 'Montre Élégante', 'prix' => 299.00, 'type' => 'Montre', 'image' => 'wishlist-item3.jpg'],
            ['nom' => 'Lunettes de Soleil', 'prix' => 89.00, 'type' => 'Lunettes', 'image' => 'product-item-1.jpg'],
            ['nom' => 'Collier Perles', 'prix' => 129.00, 'type' => 'Bijoux', 'image' => 'product-item-2.jpg'],
        ];

        foreach ($accessoires as $accessoireData) {
            $accessoire = new Accessoire();
            $accessoire->setNomProduit($accessoireData['nom']);
            $accessoire->setPrix($accessoireData['prix']);
            $accessoire->setAccessoireType($accessoireData['type']);
            $accessoire->setImage($accessoireData['image']);
            // Définir le stock (entre 10 et 50 unités aléatoirement)
            $stock = rand(10, 50);
            $accessoire->setStock($stock);
            $accessoire->setEnStock(true);
            $manager->persist($accessoire);
        }

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
            $manager->persist($promotion);
        }

        $manager->flush();

        // Associer des produits aux promotions après la persistance
        $allProduits = $manager->getRepository(\App\Entity\Produit::class)->findAll();
        $allPromotions = $manager->getRepository(Promotion::class)->findAll();

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

        $manager->flush();
    }
}

