<?php

declare(strict_types=1);

namespace Synolia\SyliusAdminOauthPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230721121011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add domains';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        if ($schema->hasTable('synolia_authorized_domain')) {
            return;
        }

        $this->addSql('CREATE TABLE synolia_authorized_domain (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(255) NOT NULL,
          is_enabled TINYINT(1) NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE synolia_authorized_domain');
    }
}
