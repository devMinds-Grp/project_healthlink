<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216142614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation_response DROP FOREIGN KEY FK_F530148B9A49365F');
        $this->addSql('ALTER TABLE donation_response ADD CONSTRAINT FK_F530148B9A49365F FOREIGN KEY (blood_donation_id) REFERENCES blood_donation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donation_response DROP FOREIGN KEY FK_F530148B9A49365F');
        $this->addSql('ALTER TABLE donation_response ADD CONSTRAINT FK_F530148B9A49365F FOREIGN KEY (blood_donation_id) REFERENCES blood_donation (id) ON DELETE SET NULL');
    }
}
