<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228164812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usercontent (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, content_id_id INT NOT NULL, score INT DEFAULT NULL, state TINYINT(1) NOT NULL, INDEX IDX_62A4CC179D86650F (user_id_id), INDEX IDX_62A4CC179487CA85 (content_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usercontent ADD CONSTRAINT FK_62A4CC179D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usercontent ADD CONSTRAINT FK_62A4CC179487CA85 FOREIGN KEY (content_id_id) REFERENCES content (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE usercontent');
    }
}
