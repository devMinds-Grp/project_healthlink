<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212230946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, card_doctor_id INT DEFAULT NULL, rdvcard_id INT DEFAULT NULL, nom_medicament VARCHAR(255) NOT NULL, dosage VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, notes VARCHAR(255) NOT NULL, INDEX IDX_1FBFB8D9F3FC1843 (card_doctor_id), UNIQUE INDEX UNIQ_1FBFB8D9AD263032 (rdvcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9F3FC1843 FOREIGN KEY (card_doctor_id) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9AD263032 FOREIGN KEY (rdvcard_id) REFERENCES Appointment (id)');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3AD263032');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3F3FC1843');
        $this->addSql('DROP TABLE card');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, card_doctor_id INT DEFAULT NULL, rdvcard_id INT DEFAULT NULL, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, disease VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_161498D3F3FC1843 (card_doctor_id), UNIQUE INDEX UNIQ_161498D3AD263032 (rdvcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3AD263032 FOREIGN KEY (rdvcard_id) REFERENCES appointment (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3F3FC1843 FOREIGN KEY (card_doctor_id) REFERENCES doctor (id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9F3FC1843');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9AD263032');
        $this->addSql('DROP TABLE prescription');
    }
}
