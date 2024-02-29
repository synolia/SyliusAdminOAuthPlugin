<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240221095851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Modify AuthorizedDomain table name to snake case';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE AuthorizedDomain RENAME TO synolia_authorized_domain ');
        $this->addSql('ALTER TABLE synolia_authorized_domain RENAME COLUMN isEnabled TO is_enabled');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE synolia_authorized_domain RENAME TO AuthorizedDomain ');
        $this->addSql('ALTER TABLE AuthorizedDomain RENAME COLUMN is_enabled TO isEnabled');
    }
}
