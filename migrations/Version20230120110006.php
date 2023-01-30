<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120110006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evento (id INT AUTO_INCREMENT NOT NULL, tramo_ini_id INT NOT NULL, tramo_fin_id INT NOT NULL, nombre VARCHAR(100) NOT NULL, descrip VARCHAR(255) NOT NULL, img VARCHAR(255) DEFAULT NULL, fecha DATETIME NOT NULL, INDEX IDX_47860B05635F8637 (tramo_ini_id), INDEX IDX_47860B05BDFED04B (tramo_fin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, ancho INT NOT NULL, longitud INT NOT NULL, min_juga INT NOT NULL, max_juga INT NOT NULL, nombre VARCHAR(100) NOT NULL, img VARCHAR(255) DEFAULT NULL, editorial VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE juego_evento (juego_id INT NOT NULL, evento_id INT NOT NULL, INDEX IDX_131B1E0113375255 (juego_id), INDEX IDX_131B1E0187A5F842 (evento_id), PRIMARY KEY(juego_id, evento_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mesa (id INT AUTO_INCREMENT NOT NULL, ancho INT NOT NULL, longitud INT NOT NULL, x INT NOT NULL, y INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participa (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, evento_id INT NOT NULL, invitacion VARCHAR(5) NOT NULL, presentado TINYINT(1) NOT NULL, INDEX IDX_E926CCDA76ED395 (user_id), INDEX IDX_E926CCD87A5F842 (evento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserva (id INT AUTO_INCREMENT NOT NULL, tramo_ini_id INT NOT NULL, tramo_fin_id INT NOT NULL, user_id INT NOT NULL, juego_id INT NOT NULL, mesa_id INT NOT NULL, fini DATETIME NOT NULL, fecha_anul DATETIME DEFAULT NULL, presentado TINYINT(1) NOT NULL, INDEX IDX_188D2E3B635F8637 (tramo_ini_id), INDEX IDX_188D2E3BBDFED04B (tramo_fin_id), INDEX IDX_188D2E3BA76ED395 (user_id), INDEX IDX_188D2E3B13375255 (juego_id), INDEX IDX_188D2E3B8BDC7AE9 (mesa_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tramo (id INT AUTO_INCREMENT NOT NULL, inicio TIME NOT NULL, fin TIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05635F8637 FOREIGN KEY (tramo_ini_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE evento ADD CONSTRAINT FK_47860B05BDFED04B FOREIGN KEY (tramo_fin_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE juego_evento ADD CONSTRAINT FK_131B1E0113375255 FOREIGN KEY (juego_id) REFERENCES juego (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE juego_evento ADD CONSTRAINT FK_131B1E0187A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participa ADD CONSTRAINT FK_E926CCDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participa ADD CONSTRAINT FK_E926CCD87A5F842 FOREIGN KEY (evento_id) REFERENCES evento (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B635F8637 FOREIGN KEY (tramo_ini_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BBDFED04B FOREIGN KEY (tramo_fin_id) REFERENCES tramo (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B13375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('ALTER TABLE reserva ADD CONSTRAINT FK_188D2E3B8BDC7AE9 FOREIGN KEY (mesa_id) REFERENCES mesa (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05635F8637');
        $this->addSql('ALTER TABLE evento DROP FOREIGN KEY FK_47860B05BDFED04B');
        $this->addSql('ALTER TABLE juego_evento DROP FOREIGN KEY FK_131B1E0113375255');
        $this->addSql('ALTER TABLE juego_evento DROP FOREIGN KEY FK_131B1E0187A5F842');
        $this->addSql('ALTER TABLE participa DROP FOREIGN KEY FK_E926CCDA76ED395');
        $this->addSql('ALTER TABLE participa DROP FOREIGN KEY FK_E926CCD87A5F842');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B635F8637');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BBDFED04B');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3BA76ED395');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B13375255');
        $this->addSql('ALTER TABLE reserva DROP FOREIGN KEY FK_188D2E3B8BDC7AE9');
        $this->addSql('DROP TABLE evento');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE juego_evento');
        $this->addSql('DROP TABLE mesa');
        $this->addSql('DROP TABLE participa');
        $this->addSql('DROP TABLE reserva');
        $this->addSql('DROP TABLE tramo');
        $this->addSql('DROP TABLE user');
    }
}
