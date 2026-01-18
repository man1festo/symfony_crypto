<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251007085631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function isTransactional(): bool
    {
        return false;
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account ADD CONSTRAINT account__user_id__fk FOREIGN KEY (user_id) REFERENCES "user" (id)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS account__user_id__ind ON account (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account DROP CONSTRAINT account__user_id__fk');
        $this->addSql('DROP INDEX CONCURENTLY IF EXISTS account__user_id__ind');
    }
}
