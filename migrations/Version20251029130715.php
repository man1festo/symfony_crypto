<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251029130715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" ADD seller_approve BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE "order" ADD buyer_approve BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "order" DROP seller_approve');
        $this->addSql('ALTER TABLE "order" DROP buyer_approve');
        $this->addSql('ALTER TABLE "user" DROP roles');
    }
}
