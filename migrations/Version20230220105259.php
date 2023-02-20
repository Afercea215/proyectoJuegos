<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220105259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05BDFED04B');
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05635F8637');
        $this->addSql('DROP INDEX IDX_47860B05635F8637 ON evento');
        $this->addSql('DROP INDEX IDX_47860B05BDFED04B ON evento');
        $this->addSql('ALTER TABLE evento DROP tramo_ini_id, DROP tramo_fin_id');
        $this->addSql('ALTER TABLE reserva CHANGE presentado presentado TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento ADD tramo_ini_id INT NOT NULL, ADD tramo_fin_id INT NOT NULL');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05BDFED04B FOREIGN KEY (tramo_fin_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05635F8637 FOREIGN KEY (tramo_ini_id) REFERENCES tramo (id)');
        $this->addSql('CREATE INDEX IDX_47860B05635F8637 ON evento (tramo_ini_id)');
        $this->addSql('CREATE INDEX IDX_47860B05BDFED04B ON evento (tramo_fin_id)');
        $this->addSql('ALTER TABLE reserva CHANGE presentado presentado TINYINT(1) DEFAULT NULL');
    }
}
