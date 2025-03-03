<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250303011258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification ADD care_response_id INT DEFAULT NULL, DROP patient_id, DROP chat_message_id, CHANGE message message VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAC1B1AE5F FOREIGN KEY (care_response_id) REFERENCES care_response (id)');
        $this->addSql('CREATE INDEX IDX_BF5476CAC1B1AE5F ON notification (care_response_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAC1B1AE5F');
        $this->addSql('DROP INDEX IDX_BF5476CAC1B1AE5F ON notification');
        $this->addSql('ALTER TABLE notification ADD chat_message_id INT DEFAULT NULL, CHANGE message message LONGTEXT NOT NULL, CHANGE care_response_id patient_id INT DEFAULT NULL');
    }
}
