<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251207235846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $connection = $this->connection;
        $schemaManager = $connection->createSchemaManager();
        
        // Vérifier si la colonne image existe déjà
        if ($schemaManager->tablesExist(['produit'])) {
            $columns = $schemaManager->listTableColumns('produit');
            $columnNames = array_map(fn($col) => $col->getName(), $columns);
            
            if (!in_array('image', $columnNames)) {
                $this->addSql('ALTER TABLE produit ADD image VARCHAR(255) DEFAULT NULL');
            }
            
            // Modifier les autres colonnes seulement si nécessaire
            $this->addSql('ALTER TABLE produit CHANGE taille taille VARCHAR(50) DEFAULT NULL, CHANGE couleur couleur VARCHAR(50) DEFAULT NULL, CHANGE genre genre VARCHAR(50) DEFAULT NULL, CHANGE accessoire_type accessoire_type VARCHAR(50) DEFAULT NULL');
        }
        
        // Modifier les autres tables si elles existent
        if ($schemaManager->tablesExist(['user'])) {
            $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        }
        
        if ($schemaManager->tablesExist(['messenger_messages'])) {
            $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE produit DROP image, CHANGE taille taille VARCHAR(50) DEFAULT \'NULL\', CHANGE couleur couleur VARCHAR(50) DEFAULT \'NULL\', CHANGE genre genre VARCHAR(50) DEFAULT \'NULL\', CHANGE accessoire_type accessoire_type VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
