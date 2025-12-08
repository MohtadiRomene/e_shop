<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration complète pour créer toutes les tables du système e-commerce
 */
final class Version20251206231000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de toutes les tables pour le système e-commerce';
    }

    public function up(Schema $schema): void
    {
        $connection = $this->connection;
        $schemaManager = $connection->createSchemaManager();
        
        // Table produit (avec héritage SINGLE_TABLE) - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['produit'])) {
            $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, nom_produit VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, taille VARCHAR(50) DEFAULT NULL, couleur VARCHAR(50) DEFAULT NULL, genre VARCHAR(50) DEFAULT NULL, pointure INT DEFAULT NULL, accessoire_type VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Table promotion - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['promotion'])) {
            $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, date_expiration DATE NOT NULL, reduction DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        }

        // Table promotion_produit (ManyToMany) - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['promotion_produit'])) {
            $this->addSql('CREATE TABLE promotion_produit (promotion_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_8E4E3C1A139DF194 (promotion_id), INDEX IDX_8E4E3C1AF347EFB (produit_id), PRIMARY KEY(promotion_id, produit_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE promotion_produit ADD CONSTRAINT FK_8E4E3C1A139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
            $this->addSql('ALTER TABLE promotion_produit ADD CONSTRAINT FK_8E4E3C1AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        }

        // Table pannier (sans référence à commande pour l'instant) - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['pannier'])) {
            $this->addSql('CREATE TABLE pannier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, commande_id INT DEFAULT NULL, INDEX IDX_8E4E3C1AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE pannier ADD CONSTRAINT FK_8E4E3C1AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        }

        // Table panier_produit - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['panier_produit'])) {
            $this->addSql('CREATE TABLE panier_produit (id INT AUTO_INCREMENT NOT NULL, panier_id INT NOT NULL, produit_id INT NOT NULL, quantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, INDEX IDX_8E4E3C1AF347EFB1 (panier_id), INDEX IDX_8E4E3C1AF347EFB2 (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_8E4E3C1AF347EFB1 FOREIGN KEY (panier_id) REFERENCES pannier (id)');
            $this->addSql('ALTER TABLE panier_produit ADD CONSTRAINT FK_8E4E3C1AF347EFB2 FOREIGN KEY (produit_id) REFERENCES produit (id)');
        }

        // Table commande (sans référence à facture pour l'instant) - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['commande'])) {
            $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, datecommande DATE NOT NULL, prixtotal DOUBLE PRECISION NOT NULL, INDEX IDX_8E4E3C1AA76ED3951 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_8E4E3C1AA76ED3951 FOREIGN KEY (user_id) REFERENCES user (id)');
        }

        // Table facture (après commande) - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['facture'])) {
            $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, UNIQUE INDEX UNIQ_8E4E3C1A82EA2E55 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_8E4E3C1A82EA2E55 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        }

        // Ajouter la référence facture_id à commande
        $this->addSql('ALTER TABLE commande ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_8E4E3C1A7F2DBC08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E4E3C1A7F2DBC08 ON commande (facture_id)');

        // Table commande_produit - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['commande_produit'])) {
            $this->addSql('CREATE TABLE commande_produit (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, produit_id INT NOT NULL, quantite INT DEFAULT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, INDEX IDX_8E4E3C1A82EA2E54 (commande_id), INDEX IDX_8E4E3C1AF347EFB3 (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_8E4E3C1A82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
            $this->addSql('ALTER TABLE commande_produit ADD CONSTRAINT FK_8E4E3C1AF347EFB3 FOREIGN KEY (produit_id) REFERENCES produit (id)');
        }

        // Ajouter la référence commande_id à pannier
        $this->addSql('ALTER TABLE pannier ADD CONSTRAINT FK_8E4E3C1A9D86650F FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E4E3C1A9D86650F ON pannier (commande_id)');

        // Table avis - seulement si elle n'existe pas
        if (!$schemaManager->tablesExist(['avis'])) {
            $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, produit_id INT NOT NULL, date DATE NOT NULL, contenu LONGTEXT NOT NULL, INDEX IDX_8E4E3C1AA76ED3952 (user_id), INDEX IDX_8E4E3C1AF347EFB4 (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8E4E3C1AA76ED3952 FOREIGN KEY (user_id) REFERENCES user (id)');
            $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8E4E3C1AF347EFB4 FOREIGN KEY (produit_id) REFERENCES produit (id)');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE commande_produit');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE panier_produit');
        $this->addSql('DROP TABLE pannier');
        $this->addSql('DROP TABLE promotion_produit');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE produit');
    }
}

