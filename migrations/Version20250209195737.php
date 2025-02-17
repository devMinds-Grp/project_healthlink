<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250209195737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Appointment (id INT AUTO_INCREMENT NOT NULL, rdvdoc_id INT DEFAULT NULL, date DATE NOT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_78A47793DBD15DB2 (rdvdoc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Doctor (id INT NOT NULL, num_tel VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, speciality VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blood_donation (id INT AUTO_INCREMENT NOT NULL, bld_don_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, date DATE NOT NULL, phone_numb VARCHAR(255) NOT NULL, INDEX IDX_908BB405C70DC856 (bld_don_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, card_doctor_id INT DEFAULT NULL, rdvcard_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, disease VARCHAR(255) NOT NULL, INDEX IDX_161498D3F3FC1843 (card_doctor_id), UNIQUE INDEX UNIQ_161498D3AD263032 (rdvcard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE care (id INT AUTO_INCREMENT NOT NULL, caregiver_id INT DEFAULT NULL, care_his_id INT DEFAULT NULL, care_patient_id INT DEFAULT NULL, date DATE NOT NULL, address VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_6113A84541946261 (caregiver_id), UNIQUE INDEX UNIQ_6113A8459F587912 (care_his_id), INDEX IDX_6113A8459C5E4318 (care_patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caregiver (id INT NOT NULL, categorie_soin VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diagnostic (id INT AUTO_INCREMENT NOT NULL, diag_patient_id INT DEFAULT NULL, fichier VARCHAR(255) NOT NULL, resultat VARCHAR(255) NOT NULL, INDEX IDX_FA7C8889D1F006BF (diag_patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation_response (id INT AUTO_INCREMENT NOT NULL, blood_donation_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_F530148B9A49365F (blood_donation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_852BBECDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_response (id INT AUTO_INCREMENT NOT NULL, forum_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_1988861C29CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, statut_soin VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacy (id INT AUTO_INCREMENT NOT NULL, phar_patient_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, num_tel VARCHAR(20) NOT NULL, type_pharmacie VARCHAR(255) NOT NULL, horaires VARCHAR(255) NOT NULL, INDEX IDX_D6C15C1E165A8709 (phar_patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, rec_cat_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, statut_reclamation VARCHAR(255) NOT NULL, INDEX IDX_CE606404DB54CFDE (rec_cat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Appointment ADD CONSTRAINT FK_78A47793DBD15DB2 FOREIGN KEY (rdvdoc_id) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE Doctor ADD CONSTRAINT FK_186CF65CBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blood_donation ADD CONSTRAINT FK_908BB405C70DC856 FOREIGN KEY (bld_don_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3F3FC1843 FOREIGN KEY (card_doctor_id) REFERENCES Doctor (id)');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D3AD263032 FOREIGN KEY (rdvcard_id) REFERENCES Appointment (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A84541946261 FOREIGN KEY (caregiver_id) REFERENCES caregiver (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8459F587912 FOREIGN KEY (care_his_id) REFERENCES history (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8459C5E4318 FOREIGN KEY (care_patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE caregiver ADD CONSTRAINT FK_27A9DEC5BF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889D1F006BF FOREIGN KEY (diag_patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE donation_response ADD CONSTRAINT FK_F530148B9A49365F FOREIGN KEY (blood_donation_id) REFERENCES blood_donation (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE forum_response ADD CONSTRAINT FK_1988861C29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBBF396750 FOREIGN KEY (id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1E165A8709 FOREIGN KEY (phar_patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404DB54CFDE FOREIGN KEY (rec_cat_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Appointment DROP FOREIGN KEY FK_78A47793DBD15DB2');
        $this->addSql('ALTER TABLE Doctor DROP FOREIGN KEY FK_186CF65CBF396750');
        $this->addSql('ALTER TABLE blood_donation DROP FOREIGN KEY FK_908BB405C70DC856');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3F3FC1843');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D3AD263032');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A84541946261');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8459F587912');
        $this->addSql('ALTER TABLE care DROP FOREIGN KEY FK_6113A8459C5E4318');
        $this->addSql('ALTER TABLE caregiver DROP FOREIGN KEY FK_27A9DEC5BF396750');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889D1F006BF');
        $this->addSql('ALTER TABLE donation_response DROP FOREIGN KEY FK_F530148B9A49365F');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDA76ED395');
        $this->addSql('ALTER TABLE forum_response DROP FOREIGN KEY FK_1988861C29CCBAD0');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBBF396750');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1E165A8709');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404DB54CFDE');
        $this->addSql('DROP TABLE Appointment');
        $this->addSql('DROP TABLE Doctor');
        $this->addSql('DROP TABLE blood_donation');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE care');
        $this->addSql('DROP TABLE caregiver');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE diagnostic');
        $this->addSql('DROP TABLE donation_response');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE forum_response');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE pharmacy');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
