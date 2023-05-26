<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519094353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Google parameters to Admin User.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          sylius_admin_user
        ADD
          google_id VARCHAR(255) DEFAULT NULL,
        ADD
          hosted_domain VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sylius_admin_user DROP googleId, DROP hostedDomain');
    }
}
