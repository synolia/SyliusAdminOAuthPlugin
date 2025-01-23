<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803085240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Microsoft id to user';
    }

    public function up(Schema $schema): void
    {
        if ($schema->getTable('sylius_admin_user')->hasColumn('microsoft_id')) {
            return;
        }

        $this->addSql('ALTER TABLE sylius_admin_user ADD microsoft_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_admin_user DROP microsoft_id');
    }
}
