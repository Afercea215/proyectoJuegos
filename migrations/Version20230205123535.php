<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205123535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB108BDC7AE9');
        $this->addSql('DROP INDEX IDX_AA08CB108BDC7AE9 ON login');
        $this->addSql('ALTER TABLE login DROP mesa_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE login ADD mesa_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB108BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('CREATE INDEX IDX_AA08CB108BDC7AE9 ON login (mesa_id)');
    }
}
