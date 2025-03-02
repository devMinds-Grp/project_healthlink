<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227191342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Appointment (id INT AUTO_INCREMENT NOT NULL, doctor_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, date DATE NOT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_78A4779387F4FB17 (doctor_id), INDEX IDX_78A477936B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blood_donation (id INT AUTO_INCREMENT NOT NULL, bld_don_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, num_tel VARCHAR(20) NOT NULL, INDEX IDX_908BB405C70DC856 (bld_don_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE care (id INT AUTO_INCREMENT NOT NULL, caregiver_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, care_user_id INT DEFAULT NULL, date DATE NOT NULL, address VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_6113A84541946261 (caregiver_id), INDEX IDX_6113A8456B899279 (patient_id), INDEX IDX_6113A845E70F4DA0 (care_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE care_response (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, care_id INT NOT NULL, patient_id INT DEFAULT NULL, soignant_id INT DEFAULT NULL, date_rep DATE NOT NULL, contenu_rep VARCHAR(255) DEFAULT NULL, INDEX IDX_6888AE46A76ED395 (user_id), INDEX IDX_6888AE46F270FD45 (care_id), INDEX IDX_6888AE466B899279 (patient_id), INDEX IDX_6888AE46453B4D3C (soignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diagnostic (id INT AUTO_INCREMENT NOT NULL, diag_user_id INT DEFAULT NULL, fichier VARCHAR(255) NOT NULL, resultat VARCHAR(255) NOT NULL, INDEX IDX_FA7C8889CB3B5707 (diag_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation_response (id INT AUTO_INCREMENT NOT NULL, blood_donation_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_F530148B9A49365F (blood_donation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, is_approved TINYINT(1) NOT NULL, INDEX IDX_852BBECDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE forum_response (id INT AUTO_INCREMENT NOT NULL, forum_id INT DEFAULT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_1988861C29CCBAD0 (forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacy (id INT AUTO_INCREMENT NOT NULL, phar_user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, num_tel VARCHAR(20) NOT NULL, type_pharmacie VARCHAR(255) NOT NULL, horaires VARCHAR(255) NOT NULL, INDEX IDX_D6C15C1EE3FC597A (phar_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, card_user_id INT DEFAULT NULL, nom_medicament VARCHAR(255) NOT NULL, dosage VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, notes LONGTEXT NOT NULL, RDVCard_id INT NOT NULL, INDEX IDX_1FBFB8D9F07459E3 (card_user_id), INDEX IDX_1FBFB8D95641EA8B (RDVCard_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_CE60640412469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_57698A6A6C6E55B5 (nom), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, care_response_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, num_tel VARCHAR(8) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, speciality VARCHAR(50) DEFAULT NULL, categorie_soin VARCHAR(50) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, imageprofile VARCHAR(255) DEFAULT NULL, statut VARCHAR(20) DEFAULT \'en attente\' NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B3D60322AC (role_id), INDEX IDX_1D1C63B3C1B1AE5F (care_response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Appointment ADD CONSTRAINT FK_78A4779387F4FB17 FOREIGN KEY (doctor_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE Appointment ADD CONSTRAINT FK_78A477936B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE blood_donation ADD CONSTRAINT FK_908BB405C70DC856 FOREIGN KEY (bld_don_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A84541946261 FOREIGN KEY (caregiver_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A8456B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care ADD CONSTRAINT FK_6113A845E70F4DA0 FOREIGN KEY (care_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE46A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE46F270FD45 FOREIGN KEY (care_id) REFERENCES care (id)');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE466B899279 FOREIGN KEY (patient_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE care_response ADD CONSTRAINT FK_6888AE46453B4D3C FOREIGN KEY (soignant_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889CB3B5707 FOREIGN KEY (diag_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE donation_response ADD CONSTRAINT FK_F530148B9A49365F FOREIGN KEY (blood_donation_id) REFERENCES blood_donation (id)');
        $this->addSql('ALTER TABLE forum ADD CONSTRAINT FK_852BBECDA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE forum_response ADD CONSTRAINT FK_1988861C29CCBAD0 FOREIGN KEY (forum_id) REFERENCES forum (id)');
        $this->addSql('ALTER TABLE pharmacy ADD CONSTRAINT FK_D6C15C1EE3FC597A FOREIGN KEY (phar_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D9F07459E3 FOREIGN KEY (card_user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D95641EA8B FOREIGN KEY (RDVCard_id) REFERENCES Appointment (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE60640412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3C1B1AE5F FOREIGN KEY (care_response_id) REFERENCES care_response (id)');
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
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE46A76ED395');
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE46F270FD45');
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE466B899279');
        $this->addSql('ALTER TABLE care_response DROP FOREIGN KEY FK_6888AE46453B4D3C');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889CB3B5707');
        $this->addSql('ALTER TABLE donation_response DROP FOREIGN KEY FK_F530148B9A49365F');
        $this->addSql('ALTER TABLE forum DROP FOREIGN KEY FK_852BBECDA76ED395');
        $this->addSql('ALTER TABLE forum_response DROP FOREIGN KEY FK_1988861C29CCBAD0');
        $this->addSql('ALTER TABLE pharmacy DROP FOREIGN KEY FK_D6C15C1EE3FC597A');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D9F07459E3');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D95641EA8B');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE60640412469DE2');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D60322AC');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3C1B1AE5F');
        $this->addSql('DROP TABLE Appointment');
        $this->addSql('DROP TABLE blood_donation');
        $this->addSql('DROP TABLE care');
        $this->addSql('DROP TABLE care_response');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE diagnostic');
        $this->addSql('DROP TABLE donation_response');
        $this->addSql('DROP TABLE forum');
        $this->addSql('DROP TABLE forum_response');
        $this->addSql('DROP TABLE pharmacy');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
