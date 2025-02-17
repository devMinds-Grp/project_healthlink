<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250215113708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A84541946261');
        $this->addSql('ALTER TABLE appointment DROP FOREIGN KEY FK_78A47793DBD15DB2');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9F3FC1843');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8459F587912');
        $this->addSql('ALTER TABLE blood_donation DROP FOREIGN KEY FK_908BB405C70DC856');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8459C5E4318');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889D1F006BF');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1E165A8709');
        $this->addSql('CREATE TABLE care_response (id INT AUTO_INCREMENT NOT NULL, care_id INT DEFAULT NULL, date_rep DATE NOT NULL, contenu_rep VARCHAR(255) NOT NULL, INDEX IDX_6888AE46F270FD45 (care_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_57698A6A6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE46F270FD45 FOREIGN KEY (care_id) REFERENCES care (id)');
        $this->addSql('ALTER TABLE caregiver DROP FOREIGN KEY FK_27A9DEC5BF396750');
        $this->addSql('ALTER TABLE doctor DROP FOREIGN KEY FK_186CF65CBF396750');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBBF396750');
        $this->addSql('DROP TABLE caregiver');
        $this->addSql('DROP TABLE doctor');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP INDEX IDX_78A47793DBD15DB2 ON appointment');
        $this->addSql('ALTER TABLE appointment ADD doctor_id INT NOT NULL, ADD patient_id INT NOT NULL, DROP rdvdoc_id');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_78A4779387F4FB17 FOREIGN KEY (doctor_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE appointment ADD CONSTRAINT FK_78A477936B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_78A4779387F4FB17 ON appointment (doctor_id)');
        $this->addSql('CREATE INDEX IDX_78A477936B899279 ON appointment (patient_id)');
        $this->addSql('ALTER TABLE blood_donation DROP FOREIGN KEY FK_908BB405C70DC856');
        $this->addSql('ALTER TABLE blood_donation ADD CONSTRAINT FK_908BB405C70DC856 FOREIGN KEY (bld_don_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A84541946261');
        $this->addSql('DROP INDEX UNIQ_6113A8459F587912 ON care');
        $this->addSql('DROP INDEX IDX_6113A8459C5E4318 ON care');
        $this->addSql('ALTER TABLE care ADD patient_id INT NOT NULL, ADD care_user_id INT DEFAULT NULL, DROP care_his_id, DROP care_patient_id, CHANGE caregiver_id caregiver_id INT NOT NULL');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8456B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A845E70F4DA0 FOREIGN KEY (care_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A84541946261 FOREIGN KEY (caregiver_id) REFERENCES utilisateur (id)');
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
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3C1B1AE5F');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('CREATE TABLE caregiver (id INT NOT NULL, categorie_soin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE doctor (id INT NOT NULL, num_tel VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, speciality VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, statut_soin VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE caregiver ADD CONSTRAINT FK_27A9DEC5BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE doctor ADD CONSTRAINT FK_186CF65CBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE46F270FD45');
        $this->addSql('DROP TABLE care_response');
        $this->addSql('DROP TABLE role');
        $this->addSql('ALTER TABLE Appointment DROP FOREIGN KEY FK_78A4779387F4FB17');
        $this->addSql('ALTER TABLE Appointment DROP FOREIGN KEY FK_78A477936B899279');
        $this->addSql('DROP INDEX IDX_78A4779387F4FB17 ON Appointment');
        $this->addSql('DROP INDEX IDX_78A477936B899279 ON Appointment');
        $this->addSql('ALTER TABLE Appointment ADD rdvdoc_id INT DEFAULT NULL, DROP doctor_id, DROP patient_id');
        $this->addSql('ALTER TABLE Appointment ADD CONSTRAINT FK_78A47793DBD15DB2 FOREIGN KEY (rdvdoc_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_78A47793DBD15DB2 ON Appointment (rdvdoc_id)');
        $this->addSql('ALTER TABLE blood_donation DROP FOREIGN KEY FK_908BB405C70DC856');
        $this->addSql('ALTER TABLE blood_donation ADD CONSTRAINT FK_908BB405C70DC856 FOREIGN KEY (bld_don_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8456B899279');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A845E70F4DA0');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A84541946261');
        $this->addSql('DROP INDEX IDX_6113A8456B899279 ON care');
        $this->addSql('DROP INDEX IDX_6113A845E70F4DA0 ON care');
        $this->addSql('ALTER TABLE care ADD care_patient_id INT DEFAULT NULL, DROP patient_id, CHANGE caregiver_id caregiver_id INT DEFAULT NULL, CHANGE care_user_id care_his_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8459C5E4318 FOREIGN KEY (care_patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8459F587912 FOREIGN KEY (care_his_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A84541946261 FOREIGN KEY (caregiver_id) REFERENCES caregiver (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6113A8459F587912 ON care (care_his_id)');
        $this->addSql('CREATE INDEX IDX_6113A8459C5E4318 ON care (care_patient_id)');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889CB3B5707');
        $this->addSql('DROP INDEX IDX_FA7C8889CB3B5707 ON diagnostic');
        $this->addSql('ALTER TABLE diagnostic CHANGE diag_user_id diag_patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889D1F006BF FOREIGN KEY (diag_patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_FA7C8889D1F006BF ON diagnostic (diag_patient_id)');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EE3FC597A');
        $this->addSql('DROP INDEX IDX_D6C15C1EE3FC597A ON pharmacy');
        $this->addSql('ALTER TABLE pharmacy CHANGE phar_user_id phar_patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1E165A8709 FOREIGN KEY (phar_patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_D6C15C1E165A8709 ON pharmacy (phar_patient_id)');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9F07459E3');
        $this->addSql('DROP INDEX IDX_1FBFB8D9F07459E3 ON prescription');
        $this->addSql('ALTER TABLE prescription CHANGE card_user_id card_doctor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9F3FC1843 FOREIGN KEY (card_doctor_id) REFERENCES doctor (id)');
        $this->addSql('CREATE INDEX IDX_1FBFB8D9F3FC1843 ON prescription (card_doctor_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B899279');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404642B8210');
        $this->addSql('DROP INDEX IDX_CE6064046B899279 ON reclamation');
        $this->addSql('DROP INDEX IDX_CE606404642B8210 ON reclamation');
        $this->addSql('ALTER TABLE reclamation DROP patient_id, DROP admin_id');
        $this->addSql('DROP INDEX IDX_1D1C63B3D60322AC ON utilisateur');
        $this->addSql('DROP INDEX IDX_1D1C63B3C1B1AE5F ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur ADD role VARCHAR(255) NOT NULL, DROP role_id, DROP care_response_id, DROP num_tel, DROP adresse, DROP speciality, DROP categorie_soin');
    }
}
