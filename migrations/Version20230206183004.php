<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230206183004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B635F8637');
        $this->addSql('DROP INDEX idx_188d2e3b635f8637 ON reserva');
        $this->addSql('CREATE INDEX IDX_188D2E3B6E801575 ON reserva (tramo_id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B635F8637 FOREIGN KEY (tramo_id) REFERENCES tramo (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B6E801575');
        $this->addSql('DROP INDEX idx_188d2e3b6e801575 ON reserva');
        $this->addSql('CREATE INDEX IDX_188D2E3B635F8637 ON reserva (tramo_id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B6E801575 FOREIGN KEY (tramo_id) REFERENCES tramo (id)');
    }
}
