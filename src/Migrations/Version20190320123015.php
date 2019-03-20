<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190320123015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE astreinte (semaine SMALLINT NOT NULL, annee INT NOT NULL, utilisateur_id INT NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_F23DC073FB88E14F (utilisateur_id), PRIMARY KEY(utilisateur_id, semaine, annee)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, numero INT NOT NULL, mdp VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, roles JSON NOT NULL, cree_a DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE astreinte ADD CONSTRAINT FK_F23DC073FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE astreinte DROP FOREIGN KEY FK_F23DC073FB88E14F');
        $this->addSql('DROP TABLE astreinte');
        $this->addSql('DROP TABLE utilisateur');
    }
}
