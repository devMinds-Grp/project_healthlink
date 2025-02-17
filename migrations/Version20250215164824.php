<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215164824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_78A4779387F4FB17 FOREIGN KEY (doctor_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_78A477936B899279 FOREIGN KEY (patient_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE blood_donation ADD CONSTRAINT FK_908BB405C70DC856 FOREIGN KEY (bld_don_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A84541946261 FOREIGN KEY (caregiver_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8456B899279 FOREIGN KEY (patient_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A845E70F4DA0 FOREIGN KEY (care_user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889CB3B5707 FOREIGN KEY (diag_user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDA76ED395 FOREIGN KEY (user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EE3FC597A FOREIGN KEY (phar_user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9F07459E3 FOREIGN KEY (card_user_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B899279 FOREIGN KEY (patient_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404642B8210 FOREIGN KEY (admin_id) REFERENCES User (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Appointment DROP FOREIGN KEY FK_78A4779387F4FB17');
        $this->addSql('ALTER TABLE Appointment DROP FOREIGN KEY FK_78A477936B899279');
        $this->addSql('ALTER TABLE blood_donation DROP FOREIGN KEY FK_908BB405C70DC856');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A84541946261');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8456B899279');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A845E70F4DA0');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889CB3B5707');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDA76ED395');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EE3FC597A');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9F07459E3');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B899279');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404642B8210');
    }
}
