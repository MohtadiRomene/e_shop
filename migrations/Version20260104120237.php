<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260104120237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes manquantes: timestamps, is_verified, is_active (User), description, stock (Produit), status, numero_commande (Commande)';
    }

    public function up(Schema $schema): void
    {
        $connection = $this->connection;
        $schemaManager = $connection->createSchemaManager();
        
        // Fonction helper pour vérifier si une colonne existe
        $columnExists = function($table, $column) use ($schemaManager) {
            if (!$schemaManager->tablesExist([$table])) {
                return false;
            }
            $columns = $schemaManager->listTableColumns($table);
            foreach ($columns as $col) {
                if ($col->getName() === $column) {
                    return true;
                }
            }
            return false;
        };
        
        // Ajout des colonnes pour la table User
        if (!$columnExists('user', 'is_verified')) {
            $this->addSql('ALTER TABLE `user` ADD is_verified TINYINT(1) DEFAULT 0 NOT NULL');
        }
        if (!$columnExists('user', 'is_active')) {
            $this->addSql('ALTER TABLE `user` ADD is_active TINYINT(1) DEFAULT 1 NOT NULL');
        }
        if (!$columnExists('user', 'created_at')) {
            $this->addSql('ALTER TABLE `user` ADD created_at DATETIME DEFAULT NULL');
            $this->addSql('UPDATE `user` SET created_at = NOW() WHERE created_at IS NULL OR created_at = \'0000-00-00 00:00:00\'');
            $this->addSql('ALTER TABLE `user` MODIFY created_at DATETIME NOT NULL');
        } else {
            // Corriger les valeurs invalides existantes
            $this->addSql('UPDATE `user` SET created_at = NOW() WHERE created_at IS NULL OR created_at = \'0000-00-00 00:00:00\'');
        }
        if (!$columnExists('user', 'updated_at')) {
            $this->addSql('ALTER TABLE `user` ADD updated_at DATETIME DEFAULT NULL');
        }
        
        // Ajout des colonnes pour la table Produit
        if (!$columnExists('produit', 'description')) {
            $this->addSql('ALTER TABLE produit ADD description LONGTEXT DEFAULT NULL');
        }
        if (!$columnExists('produit', 'en_stock')) {
            $this->addSql('ALTER TABLE produit ADD en_stock TINYINT(1) DEFAULT 1 NOT NULL');
        }
        if (!$columnExists('produit', 'stock')) {
            $this->addSql('ALTER TABLE produit ADD stock INT DEFAULT 0 NOT NULL');
        }
        if (!$columnExists('produit', 'created_at')) {
            $this->addSql('ALTER TABLE produit ADD created_at DATETIME DEFAULT NULL');
            $this->addSql('UPDATE produit SET created_at = NOW() WHERE created_at IS NULL OR created_at = \'0000-00-00 00:00:00\'');
            $this->addSql('ALTER TABLE produit MODIFY created_at DATETIME NOT NULL');
        } else {
            // Corriger les valeurs invalides existantes
            $this->addSql('UPDATE produit SET created_at = NOW() WHERE created_at IS NULL OR created_at = \'0000-00-00 00:00:00\'');
        }
        if (!$columnExists('produit', 'updated_at')) {
            $this->addSql('ALTER TABLE produit ADD updated_at DATETIME DEFAULT NULL');
        }
        
        // Ajout des colonnes pour la table Commande
        if (!$columnExists('commande', 'status')) {
            $this->addSql('ALTER TABLE commande ADD status VARCHAR(50) DEFAULT \'pending\' NOT NULL');
        }
        if (!$columnExists('commande', 'numero_commande')) {
            $this->addSql('ALTER TABLE commande ADD numero_commande VARCHAR(255) DEFAULT NULL');
        }
        if (!$columnExists('commande', 'created_at')) {
            $this->addSql('ALTER TABLE commande ADD created_at DATETIME DEFAULT NULL');
            $this->addSql('UPDATE commande SET created_at = COALESCE(Datecommande, NOW()) WHERE created_at IS NULL OR created_at = \'0000-00-00 00:00:00\'');
            $this->addSql('ALTER TABLE commande MODIFY created_at DATETIME NOT NULL');
        } else {
            // Corriger les valeurs invalides existantes
            $this->addSql('UPDATE commande SET created_at = COALESCE(Datecommande, NOW()) WHERE created_at IS NULL OR created_at = \'0000-00-00 00:00:00\'');
        }
        if (!$columnExists('commande', 'updated_at')) {
            $this->addSql('ALTER TABLE commande ADD updated_at DATETIME DEFAULT NULL');
        }
        
        // Modification du type de prix en DECIMAL pour les tables concernées (seulement si nécessaire)
        // Ces modifications sont déjà faites normalement par Doctrine, mais on les garde pour être sûr
    }

    public function down(Schema $schema): void
    {
        // Retirer les colonnes ajoutées à User
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS is_verified');
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS is_active');
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS created_at');
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS updated_at');
        
        // Retirer les colonnes ajoutées à Produit
        $this->addSql('ALTER TABLE produit DROP COLUMN IF EXISTS description');
        $this->addSql('ALTER TABLE produit DROP COLUMN IF EXISTS en_stock');
        $this->addSql('ALTER TABLE produit DROP COLUMN IF EXISTS stock');
        $this->addSql('ALTER TABLE produit DROP COLUMN IF EXISTS created_at');
        $this->addSql('ALTER TABLE produit DROP COLUMN IF EXISTS updated_at');
        
        // Retirer les colonnes ajoutées à Commande
        $this->addSql('ALTER TABLE commande DROP COLUMN IF EXISTS status');
        $this->addSql('ALTER TABLE commande DROP COLUMN IF EXISTS numero_commande');
        $this->addSql('ALTER TABLE commande DROP COLUMN IF EXISTS created_at');
        $this->addSql('ALTER TABLE commande DROP COLUMN IF EXISTS updated_at');
    }
}
