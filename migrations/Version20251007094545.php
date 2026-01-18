<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251007094545 extends AbstractMigration
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
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT order__buyer_id__fk FOREIGN KEY (buyer_id) REFERENCES "user" (id)');
        $this->addSql('CREATE INDEX CONCURRENTLY IF NOT EXISTS order__buyer_id__ind ON "order" (buyer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT order__buyer_id__fk');
        $this->addSql('DROP INDEX CONCURRENTLY IF EXISTS order__buyer_id__ind');
    }
}
