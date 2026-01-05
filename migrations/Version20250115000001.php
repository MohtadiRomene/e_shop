<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter les champs de réinitialisation de mot de passe
 */
final class Version20250115000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajout des colonnes reset_token et reset_token_expires_at pour la réinitialisation de mot de passe';
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
        
        // Ajout des colonnes pour la réinitialisation de mot de passe
        if (!$columnExists('user', 'reset_token')) {
            $this->addSql('ALTER TABLE `user` ADD reset_token VARCHAR(255) DEFAULT NULL');
        }
        if (!$columnExists('user', 'reset_token_expires_at')) {
            $this->addSql('ALTER TABLE `user` ADD reset_token_expires_at DATETIME DEFAULT NULL');
        }
    }

    public function down(Schema $schema): void
    {
        // Retirer les colonnes de réinitialisation
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS reset_token');
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS reset_token_expires_at');
    }
}
