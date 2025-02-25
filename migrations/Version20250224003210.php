<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224003210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_1FBFB8D9AD263032 ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE rdvcard_id RDVCard_id INT NOT NULL, CHANGE notes notes LONGTEXT NOT NULL');
        $this->addSql('CREATE INDEX IDX_1FBFB8D95641EA8B ON prescription (RDVCard_id)');
        $this->addSql('ALTER TABLE utilisateur ADD imageprofile VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_1FBFB8D95641EA8B ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE notes notes VARCHAR(255) NOT NULL, CHANGE RDVCard_id rdvcard_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FBFB8D9AD263032 ON prescription (rdvcard_id)');
        $this->addSql('ALTER TABLE utilisateur DROP imageprofile');
    }
}
