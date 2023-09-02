<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230902070244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horaire ADD COLUMN lang VARCHAR(3) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__horaire AS SELECT id, jours, horaire_ouverture FROM horaire');
        $this->addSql('DROP TABLE horaire');
        $this->addSql('CREATE TABLE horaire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, jours VARCHAR(255) NOT NULL, horaire_ouverture VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO horaire (id, jours, horaire_ouverture) SELECT id, jours, horaire_ouverture FROM __temp__horaire');
        $this->addSql('DROP TABLE __temp__horaire');
    }
}
