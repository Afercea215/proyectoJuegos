<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130175829 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, mesa_id INT DEFAULT NULL, user_name VARCHAR(50) NOT NULL, contrasena VARCHAR(50) NOT NULL, INDEX IDX_AA08CB108BDC7AE9 (mesa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB108BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
        $this->addSql('ALTER TABLE mesa CHANGE x x INT DEFAULT NULL, CHANGE y y INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE login DROP FOREIGN KEY FK_AA08CB108BDC7AE9');
        $this->addSql('DROP TABLE login');
        $this->addSql('ALTER TABLE mesa CHANGE x x INT NOT NULL, CHANGE y y INT NOT NULL');
    }
}
