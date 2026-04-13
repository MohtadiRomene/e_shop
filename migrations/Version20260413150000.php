<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260413150000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ensure user reset_token fields exist (fix migration ordering issue).';
    }

    public function up(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['user'])) {
            return;
        }

        $columns = $schemaManager->listTableColumns('user');
        $hasResetToken = array_key_exists('reset_token', $columns);
        $hasResetTokenExpiresAt = array_key_exists('reset_token_expires_at', $columns);

        if (!$hasResetToken) {
            $this->addSql('ALTER TABLE `user` ADD reset_token VARCHAR(255) DEFAULT NULL');
        }

        if (!$hasResetTokenExpiresAt) {
            $this->addSql(
                'ALTER TABLE `user` ADD reset_token_expires_at DATETIME DEFAULT NULL',
            );
        }
    }

    public function down(Schema $schema): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['user'])) {
            return;
        }

        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS reset_token');
        $this->addSql('ALTER TABLE `user` DROP COLUMN IF EXISTS reset_token_expires_at');
    }
}

