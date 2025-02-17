<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214210411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blood_donation ADD CONSTRAINT FK_908BB405C70DC856 FOREIGN KEY (bld_don_id) REFERENCES utilisateur (id)');
        $this->addSql('DROP INDEX UNIQ_6113A8459F587912 ON care');
        $this->addSql('DROP INDEX IDX_6113A8459C5E4318 ON care');
        $this->addSql('ALTER TABLE care ADD patient_id INT NOT NULL, ADD care_user_id INT DEFAULT NULL, DROP care_his_id, DROP care_patient_id, CHANGE caregiver_id caregiver_id INT NOT NULL');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A84541946261 FOREIGN KEY (caregiver_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8456B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A845E70F4DA0 FOREIGN KEY (care_user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_6113A8456B899279 ON care (patient_id)');
        $this->addSql('CREATE INDEX IDX_6113A845E70F4DA0 ON care (care_user_id)');
        $this->addSql('DROP INDEX IDX_FA7C8889D1F006BF ON diagnostic');
        $this->addSql('ALTER TABLE diagnostic CHANGE diag_patient_id diag_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889CB3B5707 FOREIGN KEY (diag_user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_FA7C8889CB3B5707 ON diagnostic (diag_user_id)');
        $this->addSql('DROP INDEX IDX_D6C15C1E165A8709 ON pharmacy');
        $this->addSql('ALTER TABLE pharmacy CHANGE phar_patient_id phar_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EE3FC597A FOREIGN KEY (phar_user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_D6C15C1EE3FC597A ON pharmacy (phar_user_id)');
        $this->addSql('DROP INDEX IDX_1FBFB8D9F3FC1843 ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE card_doctor_id card_user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9F07459E3 FOREIGN KEY (card_user_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9F07459E3 ON prescription (card_user_id)');
        $this->addSql('ALTER TABLE reclamation ADD patient_id INT NOT NULL, ADD admin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404642B8210 FOREIGN KEY (admin_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_CE6064046B899279 ON reclamation (patient_id)');
        $this->addSql('CREATE INDEX IDX_CE606404642B8210 ON reclamation (admin_id)');
        $this->addSql('ALTER TABLE utilisateur ADD role_id INT NOT NULL, ADD care_response_id INT DEFAULT NULL, ADD num_tel VARCHAR(20) DEFAULT NULL, ADD adresse VARCHAR(255) DEFAULT NULL, ADD speciality VARCHAR(50) DEFAULT NULL, ADD categorie_soin VARCHAR(50) DEFAULT NULL, DROP role');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3C1B1AE5F FOREIGN KEY (care_response_id) REFERENCES care_response (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3D60322AC ON utilisateur (role_id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3C1B1AE5F ON utilisateur (care_response_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE blood_donation DROP FOREIGN KEY FK_908BB405C70DC856');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A84541946261');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8456B899279');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A845E70F4DA0');
        $this->addSql('DROP INDEX IDX_6113A8456B899279 ON care');
        $this->addSql('DROP INDEX IDX_6113A845E70F4DA0 ON care');
        $this->addSql('ALTER TABLE care ADD care_patient_id INT DEFAULT NULL, DROP patient_id, CHANGE caregiver_id caregiver_id INT DEFAULT NULL, CHANGE care_user_id care_his_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6113A8459F587912 ON care (care_his_id)');
        $this->addSql('CREATE INDEX IDX_6113A8459C5E4318 ON care (care_patient_id)');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889CB3B5707');
        $this->addSql('DROP INDEX IDX_FA7C8889CB3B5707 ON diagnostic');
        $this->addSql('ALTER TABLE diagnostic CHANGE diag_user_id diag_patient_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_FA7C8889D1F006BF ON diagnostic (diag_patient_id)');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EE3FC597A');
        $this->addSql('DROP INDEX IDX_D6C15C1EE3FC597A ON pharmacy');
        $this->addSql('ALTER TABLE pharmacy CHANGE phar_user_id phar_patient_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_D6C15C1E165A8709 ON pharmacy (phar_patient_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9F07459E3');
        $this->addSql('DROP INDEX IDX_1FBFB8D9F07459E3 ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE card_user_id card_doctor_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9F3FC1843 ON prescription (card_doctor_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B899279');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404642B8210');
        $this->addSql('DROP INDEX IDX_CE6064046B899279 ON reclamation');
        $this->addSql('DROP INDEX IDX_CE606404642B8210 ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP patient_id, DROP admin_id');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3C1B1AE5F');
        $this->addSql('DROP INDEX IDX_1D1C63B3D60322AC ON utilisateur');
        $this->addSql('DROP INDEX IDX_1D1C63B3C1B1AE5F ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD role VARCHAR(255) NOT NULL, DROP role_id, DROP care_response_id, DROP num_tel, DROP adresse, DROP speciality, DROP categorie_soin');
    }
}
