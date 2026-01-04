<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter les colonnes nom, tel, adresse à la table user
 */
final class Version20251206230000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes nom, tel, adresse à la table user';
    }

    public function up(Schema $schema): void
    {
        // Ajout des colonnes manquantes à la table user (si elles n'existent pas déjà)
        $connection = $this->connection;
        $schemaManager = $connection->createSchemaManager();
        $columns = $schemaManager->listTableColumns('user');
        $columnNames = array_map(fn($col) => $col->getName(), $columns);
        
        if (!in_array('nom', $columnNames)) {
            $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) DEFAULT NULL');
        }
        if (!in_array('tel', $columnNames)) {
            $this->addSql('ALTER TABLE user ADD tel VARCHAR(20) DEFAULT NULL');
        }
        if (!in_array('adresse', $columnNames)) {
            $this->addSql('ALTER TABLE user ADD adresse VARCHAR(255) DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // Suppression des colonnes ajoutées
        $this->addSql('ALTER TABLE user DROP nom');
        $this->addSql('ALTER TABLE user DROP tel');
        $this->addSql('ALTER TABLE user DROP adresse');
    }
}

