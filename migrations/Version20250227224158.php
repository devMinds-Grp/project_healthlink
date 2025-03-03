<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227224158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care_response ADD patient_id INT DEFAULT NULL, ADD soignant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE466B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE46453B4D3C FOREIGN KEY (soignant_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_6888AE466B899279 ON care_response (patient_id)');
        $this->addSql('CREATE INDEX IDX_6888AE46453B4D3C ON care_response (soignant_id)');
        $this->addSql('DROP INDEX UNIQ_1FBFB8D9AD263032 ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE rdvcard_id RDVCard_id INT NOT NULL, CHANGE notes notes LONGTEXT NOT NULL');
        $this->addSql('CREATE INDEX IDX_1FBFB8D95641EA8B ON prescription (RDVCard_id)');
        $this->addSql('ALTER TABLE utilisateur ADD imageprofile VARCHAR(255) DEFAULT NULL, ADD reset_code VARCHAR(6) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE466B899279');
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE46453B4D3C');
        $this->addSql('DROP INDEX IDX_6888AE466B899279 ON care_response');
        $this->addSql('DROP INDEX IDX_6888AE46453B4D3C ON care_response');
        $this->addSql('ALTER TABLE care_response DROP patient_id, DROP soignant_id');
        $this->addSql('DROP INDEX IDX_1FBFB8D95641EA8B ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE notes notes VARCHAR(255) NOT NULL, CHANGE RDVCard_id rdvcard_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FBFB8D9AD263032 ON prescription (rdvcard_id)');
        $this->addSql('ALTER TABLE utilisateur DROP imageprofile, DROP reset_code');
    }
}
